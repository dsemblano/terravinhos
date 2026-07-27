<header id="banner" class="sticky banner flex w-full z-50 top-0 left-0 bg-white transition-all duration-300 shadow-md">
    <div class="container">
        {{-- Changed mt-4 to py-4, added id="banner-inner" and transition classes --}}
        <div id="banner-inner" class="flex flex-row py-4 text-white justify-between lg:justify-start lg:items-center transition-all duration-300">
            @include('partials.logo')
            @if (has_nav_menu('primary_navigation'))
                <nav id="banner-nav" class="nav-primary lg:w-full" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
                    @include('partials.menu')
                </nav>
            @endif
        </div>
    </div>
</header>