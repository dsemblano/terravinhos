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

/**
 * Posiciona o formulário de variações exatamente antes das especificações
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

// A prioridade 60 costuma ser logo após a descrição curta e antes de blocos extras
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 60);

// pdf
/**
 * Adiciona CPF/CNPJ ao PDF da WebToffee (Filtro)
 */
add_filter('wf_pklist_alter_billing_address', function ($billing_address, $template_type, $order) {
    $validate_doc = function ($value, $type) {
        $clean = preg_replace('/[^0-9]/', '', $value);
        return ($type === 'cpf') ? (strlen($clean) === 11) : (strlen($clean) === 14);
    };

    $cpf  = $order->get_meta('_billing_cpf');
    $cnpj = $order->get_meta('_billing_cnpj');

    if (!empty($cpf) && $validate_doc($cpf, 'cpf')) {
        $billing_address['nectar_cpf'] = '<br><strong>CPF:</strong> ' . esc_html($cpf);
    }

    if (!empty($cnpj) && $validate_doc($cnpj, 'cnpj')) {
        $billing_address['nectar_cnpj'] = '<br><strong>CNPJ:</strong> ' . esc_html($cnpj);
    }

    return $billing_address;
}, 10, 3);

/**
 * Adiciona CPF/CNPJ aos E-mails (Ação)
 * Corrigido para evitar o Parse Error de string inesperada
 */
add_action('woocommerce_email_customer_details', function ($order, $sent_to_admin, $plain_text) {
    $cpf  = $order->get_meta('_billing_cpf');
    $cnpj = $order->get_meta('_billing_cnpj');

    // Usamos printf para garantir que o HTML seja tratado como string
    if ($cpf) {
        printf('<p><strong>CPF:</strong> %s</p>', esc_html($cpf));
    }
    if ($cnpj) {
        printf('<p><strong>CNPJ:</strong> %s</p>', esc_html($cnpj));
    }
}, 25, 3);

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


// WhatsApp shotrcode
add_shortcode('whatsapp_button', function ($atts) {
    // Define os atributos padrão do shortcode
    $attributes = shortcode_atts([
        'number'  => '5596991955990',
        'text'    => 'Falar no WhatsApp',
        'message' => 'Olá! Gostaria de mais informações.',
        'class'   => '',
    ], $atts);

    // Renderiza a view do Blade e retorna como string para o WordPress
    return view('shortcodes.whatsapp', $attributes)->render();
});

/**
 * Shortcode para renderizar o grid de posts do blog com paginação [loop_posts]
 */
add_shortcode('loop_posts', function ($atts) {
    $atts = shortcode_atts([
        'limit'    => 6,
        'category' => 'blog',
    ], $atts, 'loop_posts');

    // Captura a página atual
    $paged = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);

    $query = new \WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => (int) $atts['limit'],
        'category_name'  => $atts['category'],
        'post_status'    => 'publish',
        'paged'          => $paged,
    ]);

    if (! $query->have_posts()) {
        return '';
    }

    $pagination = '';
    if ($query->max_num_pages > 1) {
        $big = 999999999;

        // Tipo 'plain' gera as tags <a> e <span> nativas do get_the_posts_pagination
        $links = paginate_links([
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, $paged),
            'total'     => $query->max_num_pages,
            'prev_text' => '« Anterior',
            'next_text' => 'Próximo »',
            'type'      => 'plain',
        ]);

        if ($links) {
            // Envelope idêntico ao do get_the_posts_pagination()
            $pagination  = '<nav class="navigation pagination" aria-label="' . esc_attr__('Navegação de posts', 'sage') . '">';
            $pagination .= '<h2 class="screen-reader-text sr-only">' . __('Navegação de posts', 'sage') . '</h2>';
            $pagination .= '<div class="nav-links">' . $links . '</div>';
            $pagination .= '</nav>';
        }
    }

    $output = \Roots\view('shortcodes.posts-loop', [
        'query'      => $query,
        'pagination' => $pagination,
    ])->render();

    wp_reset_postdata();

    return $output;
});

/**
 * 1. Faz os links de breadcrumbs/plugins apontarem direto para /blog/
 */
add_filter('term_link', function ($url, $term, $taxonomy) {
    if ($taxonomy === 'category' && $term->slug === 'blog') {
        return home_url('/blog/');
    }

    return $url;
}, 10, 3);

/**
 * 2. Redireciona 301 a página de arquivo /category/blog/ para /blog/
 */
add_action('template_redirect', function () {
    if (is_category('blog')) {
        wp_redirect(home_url('/blog/'), 301);
        exit;
    }
});

/**
 * 1. Faz os links de breadcrumbs/plugins da categoria de produto 'mel' apontarem para /loja/
 */
add_filter('term_link', function ($url, $term, $taxonomy) {
    if ($taxonomy === 'product_cat' && is_object($term) && $term->slug === 'mel') {
        return home_url('/loja/');
    }

    return $url;
}, 10, 3);

/**
 * 2. Redireciona 301 a página de arquivo da categoria /product-category/mel/ para /loja/
 */
add_action('template_redirect', function () {
    if (function_exists('is_product_category') && is_product_category('mel')) {
        wp_redirect(home_url('/loja/'), 301);
        exit;
    }
});
