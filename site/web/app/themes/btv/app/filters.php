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
add_filter('woocommerce_product_add_to_cart_text', function ($text) {
  return __('Comprar', 'sage');
});

// single product page
add_filter('woocommerce_product_single_add_to_cart_text', function ($text) {
  return __('Comprar', 'sage');
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

add_filter('wc_better_shipping_calculator_for_brazil_postcode_label',function () {
return 'Calcule o frete';
});

// add_filter('gettext', function ($translated_text, $text, $domain) {
//     // Só modifica se estiver na página do carrinho
//     if (is_cart() && $translated_text === 'Atualizar' && $domain === 'woocommerce') {
//         return 'Calcular Frete';
//     }

//     return $translated_text;
// }, 20, 3);

add_filter('woocommerce_my_account_my_orders_actions', function($actions, $order) {
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
add_filter( 'woocommerce_shipping_calculator_enable_country', '__return_false' );
add_filter( 'woocommerce_shipping_calculator_enable_state', '__return_false' );
add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_false' );

// remove jquery migrate

add_filter( 'wp_default_scripts', function( $scripts ){
    if(!is_admin()){
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.2.1' );
    }
} );


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

add_filter('woocommerce_breadcrumb_defaults', function () {
    return [
        'delimiter'   => '<span class="mx-2 text-gray-400">/</span>',
        'wrap_before' => '<nav class="flex text-sm font-medium mb-6 container" aria-label="Breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '<span class="hover:text-secondary">',
        'after'       => '</span>',
        'home'        => _x('Início', 'breadcrumb', 'woocommerce'),
    ];
});