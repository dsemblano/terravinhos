@php global $product; @endphp

@if ($product->get_review_count())
<div class="flex items-center gap-2 text-sm text-gray-600">
{!! wc_get_rating_html($product->get_average_rating()) !!}
<span>({{ $product->get_review_count() }} avaliações)</span>
</div>
@endif
