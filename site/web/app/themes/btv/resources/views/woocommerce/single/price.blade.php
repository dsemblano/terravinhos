@php global $product; @endphp

<div class="text-2xl font-bold text-primary">
{!! $product->get_price_html() !!}
</div>
