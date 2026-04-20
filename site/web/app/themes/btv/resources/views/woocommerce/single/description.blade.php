@php global $product; @endphp

@if ($product->get_short_description())
<div class="prose max-w-none text-gray-700">
{!! apply_filters('woocommerce_short_description', $product->get_short_description()) !!}
</div>
@endif
