@php
    echo view('pages.guest.product-detail', [
        'product' => $product ?? null,
        'related_products' => $related_products ?? collect(),
    ])->render();
@endphp
