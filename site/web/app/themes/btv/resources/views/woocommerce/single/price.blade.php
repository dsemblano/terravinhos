@php global $product; @endphp

<div class="text-2xl font-bold text-secondary mb-4" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
{!! $product->get_price_html() !!}
</div>
