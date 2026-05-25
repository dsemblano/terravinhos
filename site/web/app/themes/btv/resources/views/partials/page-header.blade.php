@if (is_woocommerce() && !is_product())
<div class="container pt-0">
    <div class="page-header bg-primary p-10 rounded-b-4xl">
        <h1 id="shop-title" class="text-white font-heading not-prose text-center text-xl lg:text-4xl uppercase">{!! $title !!}</h1>
    </div>        
</div>
    
@else
    <div class="page-header pt-8">
        <h1>{!! $title !!}</h1>
    </div>
@endif
