{{--
The Template for displaying all single products

This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.

HOWEVER, on occasion WooCommerce will need to update template files and you
(the theme developer) will need to copy the new files to your theme to
maintain compatibility. We try to do this as little as possible, but it does
happen. When this occurs the version of the template file will be bumped and
the readme will list any important changes.

@see         https://docs.woocommerce.com/document/template-structure/
@package     WooCommerce\Templates
@version     1.6.4
--}}

@extends('layouts.app')

@section('content')
    @php
        do_action('get_header', 'shop');
        do_action('woocommerce_before_main_content');
    @endphp

    @while (have_posts())
        @php
            the_post();
            $product = wc_get_product(get_the_ID());

            // Check using verified meta keys from Milo (_subscription_period or _subscription_price)
            $isMiloSubscription = $product && (
                !empty(get_post_meta($product->get_id(), '_subscription_period', true)) ||
                !empty(get_post_meta($product->get_id(), '_subscription_price', true))
            );
        @endphp

        @if ($isMiloSubscription)
            {{-- 1. MILO WINE CLUB LAYOUT --}}
            <section id="shop_single_club_product" class="bg-white pt-8 pb-16">
                <div class="container">
                    @include('partials.content-single-product-club', ['product' => $product])
                </div>
            </section>
        @else
            {{-- 2. STANDARD BOTTLE PRODUCT LAYOUT --}}
            <section id="shop_single_products" class="bg-white pt-8">
                <div class="container">
                    @php
                        wc_get_template_part('content', 'single-product');
                    @endphp
                </div>
            </section>
        @endif
    @endwhile

    @php
        do_action('woocommerce_after_main_content');
        do_action('get_sidebar', 'shop');
        do_action('get_footer', 'shop');
    @endphp

    {{-- Show related products ONLY for standard wine bottles --}}
    @if (isset($isMiloSubscription) && !$isMiloSubscription)
        <div class="container flex flex-col lg:flex-row lg:justify-between">
            {!! woocommerce_output_related_products() !!}
        </div>
    @endif
@endsection