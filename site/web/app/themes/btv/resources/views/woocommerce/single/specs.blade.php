@php global $product; @endphp

@if ($product->has_attributes())
    <div class="pt-4">
        <h3 class="font-semibold text-vinho mb-2">Características</h3>

        <dl class="text-gray-700 grid grid-cols-2 gap-6 text-lg font-heading">
            @foreach ($product->get_attributes() as $attribute)
                @php
                    // Obtém o slug (ex: pa_corpo)
                    $attr_slug = $attribute->get_name();
                    // Limpa o prefixo 'pa_' se quiser uma classe mais curta (opcional)
                    $class_name = str_replace('pa_', '', $attr_slug);

                    $name = wc_attribute_label($attr_slug);
                    $value = implode(', ', wc_get_product_terms($product->get_id(), $attr_slug, ['fields' => 'names']));
                @endphp

                <div class="li-{{ $class_name }} flex flex-row" itemprop="additionalProperty" itemscope itemtype="https://schema.org/PropertyValue">
                    <div class="flex flex-col not-prose">
                        <dt class="text-gray-600" itemprop="name">{{ $name }}</dt>
                        <dd class="font-bold text-melescuro" itemprop="value">{{ $value }}</dd>
                    </div>
                </div>
            @endforeach
            </dl>
    </div>
@endif
