@props(['product'])

@php
    /** @var WC_Product $product */
@endphp

<div class="group not-prose flex flex-col justify-start bg-white rounded-2xl shadow-xl hover:shadow-2xl transition overflow-hidden py-6 min-h-9">

    {{-- IMAGE --}}
    <a href="{{ get_permalink($product->get_id()) }}" class="block bg-gray-100">
        {!! $product->get_image('medium', [
            'class' => 'w-full h-64 sm:h-72 lg:h-80 object-cover transition-transform duration-300 group-hover:scale-105',
            'loading' => 'lazy',
            'decoding' => 'async',
        ]) !!}

        @if ($product->is_on_sale())
            <span class="absolute top-3 left-3 bg-fundo text-white text-xs font-semibold px-2 py-1 rounded">
                Oferta
            </span>
        @endif
    </a>

    {{-- CONTENT --}}
        {{-- CATEGORY --}}
        <div class="text-xs uppercase font-bold text-white bg-vinho p-2 text-center">
            {!! wc_get_product_category_list($product->get_id()) !!}
        </div>

        {{-- TITLE --}}
        <h3 class="text-base lg:text-xl font-semibold leading-tight text-primary p-5 space-y-3">
            <a href="{{ get_permalink($product->get_id()) }}">
                {{ $product->get_name() }}
            </a>
        </h3>

        {{-- SHORT DESCRIPTION --}}
        {{-- @if ($product->get_short_description())
            <p class="text-sm text-gray-600 min-h-28">
                {!! wp_strip_all_tags($product->get_short_description()) !!}
            </p>
        @endif --}}

        {{-- PRICE + CTA --}}
        <div class="flex flex-col pt-3 prose lg:prose-lg items-center">
            <div class="price text-base lg:text-xl font-bold text-primary mb-4">
                {!! $product->get_price_html() !!}
            </div>

            @php
                $outOfStock = !$product->is_in_stock();
            @endphp

            <a href="{{ $outOfStock ? '#' : '?add-to-cart=' . $product->get_id() }}" @class([
                'product-button buy-button w-full lg:w-fit text-center' => !$outOfStock,
                'px-4 py-2 rounded-lg text-sm font-semibold transition cursor-not-allowed pointer-events-none' => $outOfStock,
            ])
                aria-disabled="{{ $outOfStock ? 'true' : 'false' }}">
                {{ $outOfStock ? 'Fora de estoque' : 'Adicionar' }}
            </a>

        </div>
</div>
