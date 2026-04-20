<div x-data="{ mobileOpen: false }" class="relative" x-cloak>
    <!-- Desktop Menu (Hidden on mobile) -->
    <ul class="hidden lg:flex justify-between text-lg lg:text-xl font-heading">
        @foreach ($primary_navigation as $item)
            <li class="hover:text-primary hover:underline">
                <a href="{{ $item->url }}"
                  class="hover:text-secondary {{ $item->active ? 'text-secondary font-semibold' : 'text-white' }}"
                  @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                    {{ $item->label }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Mobile Toggle Button (Hidden on desktop) -->
    <button @click.stop="mobileOpen = !mobileOpen" class="lg:hidden p-2 relative w-10 h-10 z-50 left-0 top-4"
        :aria-expanded="mobileOpen" aria-label="Menu">
        <svg x-show="!mobileOpen" class="absolute inset-0 w-full h-full" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                color="var(--color-primary)" />
        </svg>
        <svg x-show="mobileOpen" style="display: none" class="absolute inset-0 w-full h-full" fill="none" viewBox="0 0 24 24"
            stroke="var(--color-primary)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Mobile Menu (Hidden on desktop) -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-offwhite lg:hidden pt-16" style="display: none">
        <div class="container p-4">
            @foreach ($primary_navigation as $item)
                <a href="{{ $item->url }}" @click="mobileOpen = false"
                    class="block py-3 text-xl border-b border-gray-100 {{ $item->active ? 'text-secondary' : 'text-white' }}"
                    @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                    {{ $item->label }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- @if ($item->active) aria-current="page" @endif>

@if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif> --}}