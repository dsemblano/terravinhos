
@if (! is_front_page() && ! is_home() )
<header class="banner flex w-full z-50 top-0 left-0 bg-preto hover:bg-preto hover:transition-all hover:duration-300">
@else
<header class="banner flex w-full z-50 top-0 left-0 bg-transparent hover:bg-preto hover:transition-all hover:duration-300">
@endif
    <div class="flex flex-row lg:flex-col text-white container justify-center">
        @include('partials.logo')
        @if (has_nav_menu('primary_navigation'))
            <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
            {{-- {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!} --}}
                @include('partials.menu')
            </nav>
        @endif
    </div>
</header>
