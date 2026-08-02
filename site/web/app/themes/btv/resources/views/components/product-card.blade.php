@props(['product'])

@php
    /** @var WC_Product $product */
    
    // 1. Pega as categorias e o slug
    $terms = get_the_terms($product->get_id(), 'product_cat');
    $category_name = null;
    $category_slug = null;
    $category_link = null;
    
    if ($terms && !is_wp_error($terms)) {
        $category_name = $terms[0]->name;
        $category_slug = $terms[0]->slug;
        $category_link = get_term_link($terms[0]->term_id);
    }

    // 2. Lógica inteligente de estoque para produtos simples e variáveis
    if ($product->is_type('variable')) {
        $outOfStock = true; 
        $variations = $product->get_children();
        
        foreach ($variations as $variation_id) {
            $variation = wc_get_product($variation_id);
            if ($variation && $variation->is_visible() && $variation->is_in_stock()) {
                $outOfStock = false;
                break;
            }
        }
    } else {
        $outOfStock = !$product->is_in_stock();
    }
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

        @if ($product->is_on_sale() && !$outOfStock)
            <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md shadow-sm z-10">
                Oferta
            </span>
        @endif

        {{-- Badge visual de Esgotado sobre a imagem (preserva o acesso à página) --}}
        @if ($outOfStock)
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 pointer-events-none">
                <span class="bg-white/90 text-gray-800 text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg shadow-sm">
                    Esgotado
                </span>
            </div>
        @endif
    </div>

    {{-- CONTENT BLOCK --}}
    <div class="flex flex-col flex-grow pt-4 text-center">

        {{-- CATEGORIA DO PRODUTO (Com o slug injetado na classe CSS) --}}
        @if ($category_name)
            <span class="text-xs py-2 uppercase tracking-wider font-bold mb-1 block cat-{{ $category_slug }}">
                <a href="{{ $category_link }}" class="hover:underline">
                    {{ $category_name }}
                </a>
            </span>
        @endif
        
        {{-- TITLE --}}
        <h3 class="text-sm lg:text-base font-semibold py-2 leading-snug text-secondary mb-0 hover:text-primary transition-colors">
            <a href="{{ get_permalink($product->get_id()) }}" class="">
                {{ $product->get_name() }}
            </a>
        </h3>

        {{-- PRICE + CTA --}}
        <div class="mt-auto flex flex-col items-center w-full px-4">
            
            <div class="price text-base lg:text-lg font-bold text-tertiary pb-4 pt-2">
                {!! $product->get_price_html() !!}
            </div>

            {{-- Lógica de Botões corrigida com o seu layout (@class) --}}
            @if ($outOfStock)
                <a href="#" 
                    @class([
                        'w-full bg-gray-100 text-gray-400 py-2.5 px-2 rounded-xl text-sm font-semibold text-center cursor-not-allowed pointer-events-none'
                    ])
                    aria-disabled="true">
                    Fora de estoque
                </a>
            @else
                @if ($product->is_type('variable'))
                    <a href="{{ get_permalink($product->get_id()) }}" class="cta-second w-fit text-center">
                        Ver opções
                    </a>
                @else
                    <a href="?add-to-cart={{ $product->get_id() }}" class="cta-second w-fit text-center">
                        Adicionar
                    </a>
                @endif
            @endif

        </div>
    </div>

</div>