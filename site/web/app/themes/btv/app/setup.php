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
    if (!is_page(array('cadastre-sua-vinicola'))) {
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

// Otimizações

/**
 * Função auxiliar interna para centralizar a checagem do ecossistema WooCommerce.
 * Evita redundância de processamento e mantém o código DRY.
 */
function nectar_is_woocommerce_page() {
    if (function_exists('is_woocommerce')) {
        return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
    }
    return false;
}

/**
 * Hook 1: Sniper Dinâmico por URL (Apenas para plugins embutidos no seu app.css)
 * Executado no wp_print_styles para capturar injeções agressivas dessas ferramentas.
 */
add_action('wp_print_styles', function () {
    global $wp_styles;
    
    if (empty($wp_styles->queue)) {
        return;
    }

    // CSS que SEMPRE serão removidos (já estão limpos e compilados no seu app.css global)
    $css_to_always_remove = [
        'fast-cart/fonts/fontello.css',
        'fast-cart/public/css/public.min.css',
        'fast-cart/public/css/public.css',
        'zoloblocks/build/common/style-index.css',
        // O WooCommerce muda muito os handles dos blocos, então matamos pela URL por garantia:
        'woocommerce/assets/client/blocks/wc-blocks.css'
    ];

    foreach ($wp_styles->queue as $handle) {
        if (isset($wp_styles->registered[$handle])) {
            $src = $wp_styles->registered[$handle]->src;
            
            foreach ($css_to_always_remove as $css_path) {
                if (strpos($src, $css_path) !== false) {
                    wp_dequeue_style($handle);
                    wp_deregister_style($handle);
                    break; // Match encontrado para este handle, avança para o próximo da fila
                }
            }
        }
    }
}, 100); // AUMENTADO de 1 para 100: Garante que varre a fila depois que todos os plugins já injetaram seus scripts.

/**
 * Hook 2: Remoção Estática por Handles (WooCommerce Core e Gutenberg Blocks)
 * Se NÃO for página do Woo, removemos direto pelo ID nativo. Muito mais rápido que varrer strings.
 */
add_action('wp_enqueue_scripts', function () {
    // Se for qualquer página do ecossistema WooCommerce, interrompe e mantém os estilos ativos
    if (nectar_is_woocommerce_page()) {
        return;
    }

    // Handles canônicos do WooCommerce Core e dos blocos do Gutenberg
    $woocommerce_handles = [
        // Core Styles
        'woocommerce-layout',
        'woocommerce-smallscreen',
        'woocommerce-general',
        'woocommerce_frontend_styles',
        'woocommerce-inline', // Algumas versões do Woo injetam CSS inline aqui
        
        // Block Styles (Atualizados para Woo 10.x+)
        'wc-blocks',
        'wc-blocks-style',
        'wc-blocks-packages-style',
        'wc-blocks-vendors-style',
        'wc-all-blocks-style',
        'classic-theme-styles'
    ];

    foreach ($woocommerce_handles as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }
}, 9999);

/**
 * 1. Register the 'wine_club_member' role so it's selectable in WP Admin.
 */
add_action('init', function () {
    if (!get_role('wine_club_member')) {
        add_role('wine_club_member', __('Sócio Clube Brava Terra', 'sage'), [
            'read' => true,
        ]);
    }
});

/**
 * Helper: Check if a user is an active Wine Club subscriber.
 */
function user_has_active_club_subscription($user_id = null): bool
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return false;
    }

    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }

    // Check 1: Explicit WP Role
    if (in_array('wine_club_member', (array) $user->roles)) {
        return true;
    }

    // Check 2: Query Milo / WooCommerce Subscriptions for an active record
    $active_subs = get_posts([
        'post_type'   => ['milo_subscription', 'shop_subscription'],
        'post_status' => ['active', 'wc-active', 'publish'],
        'author'      => $user_id,
        'numberposts' => 1,
        'fields'      => 'ids',
    ]);

    return !empty($active_subs);
}

/**
 * 2. Assign role automatically when Milo subscriptions are created or updated in WP Admin.
 */
add_action('save_post', function ($post_id, $post) {
    if ($post && in_array($post->post_type, ['milo_subscription', 'shop_subscription'])) {
        $user_id = $post->post_author;
        if ($user_id && in_array($post->post_status, ['active', 'wc-active', 'publish'])) {
            $user = new \WP_User($user_id);
            $user->add_role('wine_club_member');
        }
    }
}, 10, 2);

/**
 * 3. Assign role when a standard checkout order completes.
 */
add_action('woocommerce_order_status_completed', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    $user_id = $order->get_user_id();
    if (!$user_id) return;

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if ($product && $product->get_type() === 'subscription') {
            $user = new \WP_User($user_id);
            $user->add_role('wine_club_member');
            break;
        }
    }
});

/**
 * 4. Filter the active product price for Wine Club members across the entire store.
 */


/**
 * 1. Filter raw numeric price for simple products and individual variation instances.
 */
function apply_club_member_discount($price, $product) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return $price;
    }

    if ($price === '' || $price === null || !is_numeric($price) || (float) $price <= 0) {
        return $price;
    }

    // Do NOT apply discounts to subscription products themselves
    if (in_array($product->get_type(), ['subscription', 'variable-subscription'])) {
        return $price;
    }

    if (function_exists('\App\user_has_active_club_subscription') && \App\user_has_active_club_subscription()) {
        return (float) $price * 0.90;
    }

    return $price;
}

add_filter('woocommerce_product_get_price', __NAMESPACE__ . '\\apply_club_member_discount', 99, 2);
add_filter('woocommerce_product_variation_get_price', __NAMESPACE__ . '\\apply_club_member_discount', 99, 2);

/**
 * 2. NEW: Filter the variation prices array for Variable Products (min/max range calculations).
 */
add_filter('woocommerce_variation_prices', function ($prices_array, $product, $for_display) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return $prices_array;
    }

    if (in_array($product->get_type(), ['subscription', 'variable-subscription'])) {
        return $prices_array;
    }

    if (function_exists('\App\user_has_active_club_subscription') && \App\user_has_active_club_subscription()) {
        if (!empty($prices_array['price']) && is_array($prices_array['price'])) {
            foreach ($prices_array['price'] as $variation_id => $price) {
                if (is_numeric($price) && (float) $price > 0) {
                    $prices_array['price'][$variation_id] = (float) $price * 0.90;
                }
            }
        }
    }

    return $prices_array;
}, 99, 3);

/**
 * 3. Force WooCommerce variation price transient cache to differentiate members vs guests.
 */
add_filter('woocommerce_variation_prices_hash', function ($hash) {
    $is_member = function_exists('\App\user_has_active_club_subscription') && \App\user_has_active_club_subscription();
    $hash[] = $is_member ? 'wine_club_member' : 'guest_or_regular';
    return $hash;
});

/**
 * 4. Format HTML price display across shop, archive, and product pages.
 */
add_filter('woocommerce_get_price_html', function ($price_html, $product) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return $price_html;
    }

    // Do nothing if product has no price set or price HTML is empty
    if (empty($price_html) || $product->get_price() === '' || $product->get_price() === null) {
        return $price_html;
    }

    // Do not alter subscription products
    if (in_array($product->get_type(), ['subscription', 'variable-subscription'])) {
        return $price_html;
    }

    if (function_exists('\App\user_has_active_club_subscription') && \App\user_has_active_club_subscription()) {
        
        // Simple & Single Variation Products
        if ($product->is_type('simple') || $product->is_type('variation')) {
            $regular_price = $product->get_regular_price();
            $discounted_price = $product->get_price();

            if ($regular_price && (float) $regular_price > (float) $discounted_price) {
                $price_html = wc_format_sale_price(
                    wc_price($regular_price),
                    wc_price($discounted_price)
                );
            }
        } 
        // Variable Products
        elseif ($product->is_type('variable')) {
            $min_reg  = $product->get_variation_regular_price('min', true);
            $max_reg  = $product->get_variation_regular_price('max', true);
            $min_disc = $product->get_variation_price('min', true);
            $max_disc = $product->get_variation_price('max', true);

            if ($min_disc && $max_disc) {
                if ($min_disc === $max_disc && $min_reg === $max_reg) {
                    $price_html = wc_format_sale_price(
                        wc_price($min_reg),
                        wc_price($min_disc)
                    );
                } else {
                    $reg_range  = ($min_reg === $max_reg) ? wc_price($min_reg) : wc_format_price_range($min_reg, $max_reg);
                    $disc_range = ($min_disc === $max_disc) ? wc_price($min_disc) : wc_format_price_range($min_disc, $max_disc);

                    $price_html = wc_format_sale_price($reg_range, $disc_range);
                }
            }
        }

        $price_html .= ' <span class="text-xs font-normal text-vinho opacity-75 ml-1">(Preço de Sócio)</span>';
    }

    return $price_html;
}, 100, 2);