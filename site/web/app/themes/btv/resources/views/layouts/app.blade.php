<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @include('sections.header')
      @if (! is_front_page() && ! is_home() && ! is_woocommerce())
      <main id="main" class="main container prose lg:prose-xl prose-p:text-xl mx-auto max-w-none bg-fundo">
      @elseif ( is_woocommerce() )
      <main id="main" class="main main-woo prose lg:prose-xl prose-p:text-xl prose-ul:p-0 mx-auto max-w-none bg-fundo">
      @else
      <main id="main" class="main prose lg:prose-xl prose-p:text-xl prose-ul:p-0 mx-auto max-w-none">
      @endif
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar bg-orange-400">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
