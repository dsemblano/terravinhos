{{-- resources/views/partials/content-single-product-club.blade.php --}}
@props(['product'])

<div id="product-{{ $product->get_id() }}" {{ wc_product_class('grid grid-cols-1 lg:grid-cols-2 gap-12 items-center', $product) }}>

    {{-- Left: Club Image / Banner --}}
    <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-vinho/5 p-4">
        {!! $product->get_image('large', [
            'class' => 'w-full h-auto object-cover rounded-2xl',
            'alt'   => $product->get_name(),
        ]) !!}
    </div>

    {{-- Right: Club Content & Add-to-Cart --}}
    <div class="flex flex-col space-y-6">
        <span class="inline-block bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full w-max">
            Clube de Assinatura
        </span>

        <h1 class="text-3xl lg:text-4xl font-serif font-bold text-vinho">
            {{ $product->get_name() }}
        </h1>

        <div class="price text-2xl font-bold text-tertiary">
            {!! $product->get_price_html() !!}
        </div>

        <div class="prose text-gray-600">
            {!! $product->get_description() !!}
        </div>

        {{-- Club Benefits Bullet Points --}}
        <ul class="space-y-3 border-y border-gray-100 py-4 text-sm text-gray-700">
            <li class="flex items-center gap-2">
                <span class="text-amber-500 font-bold">✓</span> 10% de desconto em todo o catálogo da Brava Terra
            </li>
            <li class="flex items-center gap-2">
                <span class="text-amber-500 font-bold">✓</span> Seleção mensal exclusiva enviada na sua casa
            </li>
            <li class="flex items-center gap-2">
                <span class="text-amber-500 font-bold">✓</span> Cancele ou pause quando quiser
            </li>
        </ul>

        {{-- Native WooCommerce / Milo Purchase Form Hook --}}
        <div class="mt-4">
            @php
                do_action('woocommerce_' . $product->get_type() . '_add_to_cart');
            @endphp
        </div>
    </div>

</div>