<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    register_nav_menus([
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

// Woocommerce customizations

// add_action( 'wp_enqueue_scripts', function() {
//     if ( ! class_exists( 'WooCommerce' ) ) {
//         return;
//     }

//     // Allow assets only on Woo pages
//     if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {

//         global $wp_styles, $wp_scripts;

//         // Dequeue all WooCommerce styles
//         foreach ( $wp_styles->queue as $handle ) {
//             if ( strpos( $handle, 'woocommerce' ) !== false || strpos( $handle, 'wc-blocks' ) !== false ) {
//                 wp_dequeue_style( $handle );
//             }
//         }

//         // Dequeue all WooCommerce scripts
//         foreach ( $wp_scripts->queue as $handle ) {
//             if ( strpos( $handle, 'woocommerce' ) !== false || strpos( $handle, 'wc-blocks' ) !== false || strpos( $handle, 'wc-' ) === 0 ) {
//                 wp_dequeue_script( $handle );
//             }
//         }
//     }
// }, 99 );


// Remove brands.css woocommerce
// add_action('wp_enqueue_scripts', function () {
//     wp_deregister_style('brands-styles');
// });

// Limpa ordem padrão do WooCommerce
add_action('wp', function () {

    // Remove tudo da summary padrão
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
});

// Reinserir elementos na summary em nova ordem
add_action('woocommerce_single_product_summary', function () {
    echo view('woocommerce.single.title')->render();
}, 5);

add_action('woocommerce_single_product_summary', function () {
    echo view('woocommerce.single.rating')->render();
}, 10);

add_action('woocommerce_single_product_summary', function () {
    echo view('woocommerce.single.price')->render();
}, 15);

add_action('woocommerce_single_product_summary', function () {
    echo view('woocommerce.single.description')->render();
}, 20);

add_action('woocommerce_single_product_summary', function () {
    echo view('woocommerce.single.specs')->render();
}, 25);

// add_action('woocommerce_single_product_summary', function () {
//   echo view('woocommerce.single.cta')->render();
// }, 30);

// add_action('mytheme_product_cta', function () {
//   echo view('woocommerce.single.cta')->render();
// });

// Source - https://stackoverflow.com/a/64867693
// Posted by Jeremiah Deasey
// Retrieved 2026-01-10, License - CC BY-SA 4.0


add_action('wp_enqueue_scripts', function () {
    if (!is_page(array('contato'))) {
        wp_dequeue_script('contact-form-7');
        wp_dequeue_style('contact-form-7');

        /* these are both needed */
        wp_dequeue_script('wpcf7-recaptcha');
        wp_dequeue_script('google-recaptcha');
    }
}, 99);

/**
 * Posiciona o formulário de variações exatamente antes das especificações
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

// A prioridade 60 costuma ser logo após a descrição curta e antes de blocos extras
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 60);
