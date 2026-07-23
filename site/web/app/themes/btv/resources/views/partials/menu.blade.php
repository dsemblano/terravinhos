<div x-data="{ mobileOpen: false }" class="relative" x-cloak>
    <!-- Desktop Menu (Hidden on mobile) -->
    <ul class="hidden lg:flex text-lg lg:text-xl justify-around font-heading">
        @foreach ($primary_navigation as $item)
            {{-- 1. Always render the standard menu item (This keeps "Carrinho" visible) --}}
            <li class="{{ $item->classes }} hover:text-secondary hover:underline transition duration-300">
                <a href="{{ $item->url }}"
                    class="hover:text-secondary {{ $item->active ? 'text-secondary font-semibold' : 'text-gray-800' }}"
                    @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                    {{ $item->label }}
                </a>
            </li>

            {{-- 2. If this item is the cart, append the Fast Cart item right after it --}}
            @if (function_exists('wc_get_cart_url') && $item->url === wc_get_cart_url())
                <li class="menu-item fc-menu-item menu-item-type-fc">
                    <a class="fc-cart-menu-item-link" href="{{ $item->url }}">
                        <span class="fc-menu-item-inner" data-count="{{ WC()->cart->get_cart_contents_count() }}">
                            <span class="fc-icon-shopping-basket"></span>
                            <span class="fc-menu-item-inner-subtotal">{!! WC()->cart->get_cart_subtotal() !!}</span>
                        </span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

    <!-- Mobile Toggle Button (Hidden on desktop) -->
    <button @click.stop="mobileOpen = !mobileOpen" class="lg:hidden p-2 relative w-10 h-10 z-50"
        :aria-expanded="mobileOpen" aria-label="Menu">
        <svg x-show="!mobileOpen" class="absolute inset-0 w-full h-full" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                color="var(--color-secondary)" />
        </svg>
        <svg x-show="mobileOpen" style="display: none" class="absolute inset-0 w-full h-full" fill="none" viewBox="0 0 24 24"
            stroke="var(--color-secondary)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Mobile Menu (Hidden on desktop) -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-offsecondary lg:hidden pt-10" style="display: none">
        <div class="container p-4 bg-white shadow-xl">
            @foreach ($primary_navigation as $item)
                {{-- Render the standard mobile link --}}
                <a href="{{ $item->url }}" @click="mobileOpen = false"
                    class="{{ $item->classes }} block py-3 text-xl border-b border-gray-100 {{ $item->active ? 'text-secondary' : 'text-gray-800' }}"
                    @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                    {{ $item->label }}
                </a>

                {{-- Append the mobile Fast Cart layout link right underneath it --}}
                @if (function_exists('wc_get_cart_url') && $item->url === wc_get_cart_url())
                    <a href="{{ $item->url }}" @click="mobileOpen = false"
                        class="fc-cart-menu-item-link block py-3 text-xl border-b border-gray-100 {{ $item->active ? 'text-secondary' : 'text-gray-800' }}"
                        @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                        <span class="fc-menu-item-inner" data-count="{{ WC()->cart->get_cart_contents_count() }}">
                            <span class="fc-icon-shopping-basket"></span>
                            <span class="fc-menu-item-inner-subtotal">{!! WC()->cart->get_cart_subtotal() !!}</span>
                        </span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>

{{-- @if ($item->active) aria-current="page" @endif>

@if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif> --}}