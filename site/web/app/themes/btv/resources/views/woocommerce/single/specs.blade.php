@php global $product; @endphp

@if ($product->has_attributes())
<div class="pt-4">
<h3 class="font-semibold text-primary mb-2">Especificações</h3>

<ul class="text-sm text-gray-700 space-y-1">
@foreach ($product->get_attributes() as $attribute)
@php
$name = wc_attribute_label($attribute->get_name());
$value = implode(', ', wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']));
@endphp

<li>
<strong>{{ $name }}:</strong> {{ $value }}
</li>
@endforeach
</ul>
</div>
@endif
