@php
    echo view('pages.guest.catalog', [
        'products' => $products,
        'catalogOptions' => $catalogOptions,
    ])->render();
@endphp
