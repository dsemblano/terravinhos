@props(['product'])

@php
    /** @var WC_Product $product */
@endphp

<div class="group not-prose relative flex flex-col h-full bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden pb-5">

    {{-- IMAGE CONTAINER --}}
    <div class="relative aspect-square w-full overflow-hidden bg-gray-100">
        <a href="{{ get_permalink($product->get_id()) }}" class="block h-full w-full">
            {!! $product->get_image('medium', [
                'class' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
                'loading' => 'lazy',
                'decoding' => 'async',
            ]) !!}
        </a>

        @if ($product->is_on_sale())
            <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md shadow-sm z-10">
                Oferta
            </span>
        @endif
    </div>

    {{-- CONTENT BLOCK --}}
    <div class="flex flex-col flex-grow px-4 pt-4 text-center">
        
        {{-- TITLE (Displays fully, no matter how many lines) --}}
        <h3 class="text-sm lg:text-base font-semibold leading-snug text-vinho mb-0 hover:text-primary transition-colors">
            <a href="{{ get_permalink($product->get_id()) }}" class="">
                {{ $product->get_name() }}
            </a>
        </h3>

        {{-- PRICE + CTA (mt-auto pushes this entire block to the very bottom) --}}
        <div class="mt-auto flex flex-col items-center w-full">
            
            <div class="price text-base lg:text-lg font-bold text-secondary pb-4 pt-2">
                {!! $product->get_price_html() !!}
            </div>

            @php
                $outOfStock = !$product->is_in_stock();
            @endphp

            @if( ! $product->is_type('variable') )
            <a href="{{ $outOfStock ? '#' : '?add-to-cart=' . $product->get_id() }}" 
                @class([
                    'w-full bg-primary text-white py-2.5 px-4 rounded-xl text-sm font-semibold transition duration-200 hover:bg-opacity-90 text-center' => !$outOfStock,
                    'w-full bg-gray-100 text-gray-400 py-2.5 px-2 rounded-xl text-sm font-semibold text-center cursor-not-allowed pointer-events-none' => $outOfStock,
                ])
                aria-disabled="{{ $outOfStock ? 'true' : 'false' }}">
                {{ $outOfStock ? 'Fora de estoque' : 'Adicionar' }}
            </a>
            @endif

        </div>
    </div>

</div>
