@props(['product'])

@php
    /** @var WC_Product $product */
@endphp

<div class="group not-prose bg-offwhite rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

    {{-- IMAGE --}}
    <a href="{{ get_permalink($product->get_id()) }}" class="block bg-gray-100">
        {!! $product->get_image('medium', [
            'class' => 'w-full h-64 sm:h-72 lg:h-80 object-cover transition-transform duration-300 group-hover:scale-105',
            'loading' => 'lazy',
            'decoding' => 'async',
        ]) !!}

        @if ($product->is_on_sale())
            <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded">
                Oferta
            </span>
        @endif
    </a>

    {{-- CONTENT --}}
    <div class="p-5 space-y-3">

        {{-- CATEGORY --}}
        <div class="text-xs uppercase tracking-wide text-gray-400">
            {!! wc_get_product_category_list($product->get_id()) !!}
        </div>

        {{-- TITLE --}}
        <h3 class="text-xl font-semibold leading-tight text-primary">
            <a href="{{ get_permalink($product->get_id()) }}">
                {{ $product->get_name() }}
            </a>
        </h3>

        {{-- SHORT DESCRIPTION --}}
        @if ($product->get_short_description())
            <p class="text-sm text-gray-600 min-h-28">
                {!! wp_strip_all_tags($product->get_short_description()) !!}
            </p>
        @endif

        {{-- PRICE + CTA --}}
        <div class="flex items-center justify-between pt-3 min-h-28">
            <span class="text-xl font-bold text-primary">
                {!! $product->get_price_html() !!}
            </span>

            @php
                $outOfStock = !$product->is_in_stock();
            @endphp

            <a href="{{ $outOfStock ? '#' : '?add-to-cart=' . $product->get_id() }}" @class([
                'product-button' => !$outOfStock,
                'px-4 py-2 rounded-lg text-sm font-semibold transition bg-gray-300 text-gray-500 cursor-not-allowed pointer-events-none' => $outOfStock,
            ])
                aria-disabled="{{ $outOfStock ? 'true' : 'false' }}">
                {{ $outOfStock ? 'Fora de estoque' : 'Comprar' }}
            </a>

        </div>

    </div>
</div>
