<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Ver mais', 'sage'));
});

// New navigation menu
add_filter('sage/blade/data', function ($data) {
    $data['primary_navigation'] = \Log1x\Navi\Facades\Navi::build('primary_navigation')->toArray();
    return $data;
  });

/**
 * Woocommerce filters
 **/

// shop page
// add_filter('woocommerce_product_add_to_cart_text', function ($text) {
//   return __('Adicionar', 'sage');
// });

// single product page
add_filter('woocommerce_product_single_add_to_cart_text', function ($text) {
  return __('Adicionar', 'sage');
});

add_filter('woocommerce_product_related_products_heading', function ($text) {
  return __('Você pode comprar também:', 'sage');
});

// add_filter('woocommerce_default_address_fields', function ($fields) {
//     $fields['postcode']['required'] = true;
//     $fields['postcode']['placeholder'] = 'Digite seu CEP';
//     return $fields;
// });

remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);

add_action('woocommerce_proceed_to_checkout', function () {
  echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="checkout-button button alt wc-forward bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">' . __('Finalizar Compra', 'sage') . '</a>';
}, 20);

add_filter('woocommerce_ship_to_different_address_checked', '__return_false');

add_filter('wc_better_shipping_calculator_for_brazil_postcode_label', function () {
  return 'Calcule o frete';
});

// add_filter('gettext', function ($translated_text, $text, $domain) {
//   // Só modifica se estiver na página do carrinho
//   if (is_cart() && $translated_text === 'Atualizar' && $domain === 'woocommerce') {
//     return 'Calcular Frete';
//   }

//   return $translated_text;
// }, 20, 3);

add_filter('woocommerce_my_account_my_orders_actions', function ($actions, $order) {
  // Remove todas as ações da lista de pedidos
  if (is_wc_endpoint_url('order-received')) {
    return [];
  }
  return $actions;
}, 10, 2);

// Mostrar preço tachado + texto "Fora de estoque" no loop da loja
// add_filter('woocommerce_get_price_html', function($price, $product) {
//     if ( ! $product->is_in_stock() ) {
//         $price = '<span class="price-out-of-stock" style="display:block;">'
//                . '<span class="old-price" style="text-decoration:line-through; opacity:0.7;">' . $price . '</span>'
//                . '<br><span class="stock out-of-stock" style="color:#cc0000;font-weight:bold;">Fora de estoque</span>'
//                . '</span>';
//     }
//     return $price;
// }, 10, 2);

// add_filter('woocommerce_default_address_fields', function ( $address_fields ) {
//     $address_fields['postcode']['placeholder'] = 'Digite seu CEP';
//     return $address_fields;
// }, 20, 1);


// Remove all possible fields
// add_filter( 'woocommerce_checkout_fields', function($fields) {
//     // Billing fields
//     unset( $fields['billing']['billing_state'] );
//     unset( $fields['billing']['billing_address_1'] );
//     unset( $fields['billing']['billing_address_2'] );
//     unset( $fields['billing']['billing_city'] );
//     unset( $fields['billing']['billing_postcode'] );
//     unset( $fields['billing']['billing_country'] );

//     // Shipping fields
//     unset( $fields['shipping']['shipping_state'] );
//     unset( $fields['shipping']['shipping_address_1'] );
//     unset( $fields['shipping']['shipping_address_2'] );
//     unset( $fields['shipping']['shipping_city'] );
//     unset( $fields['shipping']['shipping_postcode'] );
//     unset( $fields['shipping']['shipping_country'] );

//     return $fields;
//   });

// Remove country/state/city from cart shipping calculator
add_filter('woocommerce_shipping_calculator_enable_country', '__return_false');
add_filter('woocommerce_shipping_calculator_enable_state', '__return_false');
add_filter('woocommerce_shipping_calculator_enable_city', '__return_false');

// remove jquery migrate

add_filter('wp_default_scripts', function ($scripts) {
  if (!is_admin()) {
    $scripts->remove('jquery');
    $scripts->add('jquery', false, array('jquery-core'), '1.2.1');
  }
});


/**
 * Defer Cart for WooCommerce CSS
 */

add_filter('style_loader_tag', function ($html, $handle, $href, $media) {

  if (strpos($href, 'cart-for-woocommerce/assets/css/style.min.css') === false) {
    return $html;
  }

  return <<<HTML
<link rel="preload" href="{$href}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript>
  <link rel="stylesheet" href="{$href}">
</noscript>
HTML;
}, 10, 4);


add_filter('woocommerce_dropdown_variation_attribute_options_args', function ($args) {
  // Adiciona uma classe customizada para facilitar o alvo no Tailwind
  $args['class'] = 'custom-variation-select border-gray-200 rounded-lg focus:ring-primary';
  return $args;
});

// para o plugin pdf
add_filter('wf_pklist_alter_billing_address', function($billing_address, $template_type, $order) {
    
    // Callback anônimo para validação de integridade do documento
    $validate_doc = function($value, $type) {
        $clean_value = preg_replace('/[^0-9]/', '', $value);
        return ($type === 'cpf') ? (strlen($clean_value) === 11) : (strlen($clean_value) === 14);
    };

    $cpf  = $order->get_meta('_billing_cpf');
    $cnpj = $order->get_meta('_billing_cnpj');

    // Injeção validada do CPF
    if (!empty($cpf) && $validate_doc($cpf, 'cpf')) {
        $billing_address['nectar_cpf'] = '<br><strong>CPF:</strong> ' . esc_html($cpf);
    }

    // Injeção validada do CNPJ
    if (!empty($cnpj) && $validate_doc($cnpj, 'cnpj')) {
        $billing_address['nectar_cnpj'] = '<br><strong>CNPJ:</strong> ' . esc_html($cnpj);
    }

    return $billing_address;
}, 10, 3);

/**
 * Corrige o bug de imagem quebrada (googleusercontent) nos e-mails do WooCommerce/WebToffee
 */
add_action('woocommerce_email_order_item_meta', function($item_id, $item, $order, $plain_text) {
    if ($plain_text) {
        return;
    }

    $product = $item->get_product();
    if (!$product) {
        return;
    }

    // Pega o ID da imagem destacada diretamente do produto pai (trata variações também)
    $image_id = $product->get_image_id();
    
    // Se for variação e não tiver imagem própria, pega a do produto pai
    if (!$image_id && $product->is_type('variation')) {
        $parent_product = wc_get_product($product->get_parent_id());
        if ($parent_product) {
            $image_id = $parent_product->get_image_id();
        }
    }

    if ($image_id) {
        // Busca a URL real da imagem cadastrada na biblioteca de mídia
        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');

        if ($image_url) {
            // Garante o protocolo HTTPS absoluto para o Gmail não bloquear
            $image_url = str_replace('http://', 'https://', $image_url);

            // Injeta a tag forçada eliminando o lixo do proxy do Gmail
            printf(
                '<div style="margin: 10px 0; display: inline-block; vertical-align: middle;">' .
                '<img src="%s" alt="%s" width="48" height="48" style="border: 1px solid #e5e5e5; display: block; max-width: 48px; height: auto;" />' .
                '</div>',
                esc_url($image_url),
                esc_attr($product->get_name())
            );
        }
    }
}, 5, 4); // Alterado para prioridade 5 para rodar ANTES do bloco padrão da WebToffee

/**
 * Carregar o CSS dos blocos do Gutenberg apenas quando forem utilizados na página.
 */
add_filter('should_load_separate_core_block_assets', '__return_true');

add_filter( 'gettext', function( $translated_text, $text, $domain ) {
    // You can filter by domain e.g. 'woocommerce-fast-cart' if needed
    switch ( $text ) {
        case 'Shopping Cart':
            return 'Carrinho de Compras';
            
        case 'Empty Cart':
            return 'Esvaziar carrinho';
            
        case 'Your cart is currently empty.':
            return 'Seu carrinho está vazio.';
    }

    return $translated_text;
}, 20, 3 );

// WooCommerce breadcrumb
// add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
// function wcc_change_breadcrumb_delimiter( $defaults ) {
// 	// Change the breadcrumb delimeter from '/' to '>'
// 	$defaults['delimiter'] = ' &gt; ';
// 	return $defaults;
// }

/**
 * Padroniza a estrutura do Breadcrumb do WooCommerce para o padrão TSF
 */
add_filter('woocommerce_breadcrumb_defaults', function () {
    return [
        'delimiter'   => '', // Vazio pois o hexágono é injetado via CSS ::after
        'wrap_before' => '<nav aria-label="Breadcrumb" class="wc-breadcrumb container mt-6"><ol>',
        'wrap_after'  => '</ol></nav>',
        'before'      => '<li class="breadcrumb-item">',
        'after'       => '</li>',
        'home'        => _x('Início', 'breadcrumb', 'woocommerce'),
    ];
});
