{{-- resources/views/partials/content-single-product-club.blade.php --}}
@props(['product'])

<div id="product-{{ $product->get_id() }}"
    {{ wc_product_class('grid grid-cols-1 lg:grid-cols-2 gap-12 items-center', $product) }}>

    {{-- Left: Club Image / Banner --}}
    <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-vinho/5 p-4">
        {!! $product->get_image('large', [
            'class' => 'w-full h-auto object-cover rounded-2xl',
            'alt' => $product->get_name(),
        ]) !!}
    </div>

    {{-- Right: Club Content & Add-to-Cart --}}
    <div class="flex flex-col space-y-6">
        <span
            class="inline-block bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full w-max">
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
        {{-- Native WooCommerce / Milo Purchase Form OR Already Subscribed Banner --}}
        <div class="mt-6">
            @if (function_exists('\App\user_has_active_club_subscription') && \App\user_has_active_club_subscription())
                {{-- ALREADY SUBSCRIBED STATE --}}
                <div class="bg-vinho/5 border border-vinho/20 rounded-2xl p-6 text-center space-y-3">
                    <div
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-vinho text-amber-400 font-bold text-lg">
                        ✓
                    </div>

                    <h3 class="text-lg font-bold text-vinho mb-0">
                        Você já é um Sócio do Clube Brava Terra!
                    </h3>

                    <p class="text-sm text-gray-600">
                        Sua assinatura está ativa. Aproveite o seu desconto de 10% em todo o nosso catálogo de vinhos.
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ wc_get_page_permalink('shop') }}" class="cta-second text-xs font-semibold">
                            Explorar Catálogo de Vinhos
                        </a>
                        <a href="{{ wc_get_account_endpoint_url('subscriptions') }}"
                            class="text-xs text-vinho underline hover:text-primary font-medium flex items-center justify-center">
                            Gerenciar Minha Assinatura
                        </a>
                    </div>
                </div>
            @else
                {{-- STANDARD SUBSCRIBE BUTTON (NON-MEMBERS / GUESTS) --}}
                @php
                    do_action('woocommerce_' . $product->get_type() . '_add_to_cart');
                @endphp
            @endif
        </div>
    </div>

</div>
