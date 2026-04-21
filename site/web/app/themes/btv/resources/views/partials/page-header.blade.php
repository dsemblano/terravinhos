@if (! is_front_page() && ! is_home() )
<div
    class="prose-h1:mt-8 page-header container">
    <h1 class="{{ !is_woocommerce() && !is_cart() && !is_checkout() ? ' container aqui' : '' }}">{!! $title !!}</h1>
</div>
@endif