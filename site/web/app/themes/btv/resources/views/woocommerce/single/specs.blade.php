@php global $product; @endphp

@if ($product->has_attributes())
    <div class="pt-4">
        <h3 class="font-semibold text-primary mb-2">Características</h3>

        <ul class="text-gray-700 list-none grid grid-cols-2 text-xl">
            @foreach ($product->get_attributes() as $attribute)
                @php
                    // Obtém o slug (ex: pa_corpo)
                    $attr_slug = $attribute->get_name();
                    // Limpa o prefixo 'pa_' se quiser uma classe mais curta (opcional)
                    $class_name = str_replace('pa_', '', $attr_slug);

                    $name = wc_attribute_label($attr_slug);
                    $value = implode(', ', wc_get_product_terms($product->get_id(), $attr_slug, ['fields' => 'names']));
                @endphp

                <li class="li-{{ $class_name }}">
                    <strong>{{ $name }}:</strong> {{ $value }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
