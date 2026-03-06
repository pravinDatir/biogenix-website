@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Product CRUD</h1>
        <p class="muted">Create, edit, and delete products with multiple images and variants.</p>
    </div>

    <div class="card">
        <h2>Create Product</h2>
        <form method="POST" action="{{ url('/products-crud') }}" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="create_name">Name</label>
                <input id="create_name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="create_slug">Slug (optional)</label>
                <input id="create_slug" name="slug" value="{{ old('slug') }}">
            </div>

            <div class="field">
                <label for="create_description">Description</label>
                <textarea id="create_description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="field">
                <label for="create_category_id">Category</label>
                <select id="create_category_id" name="category_id">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id') === (int) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="create_subcategory_id">Subcategory</label>
                <select id="create_subcategory_id" name="subcategory_id">
                    <option value="">Select subcategory</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" @selected((int) old('subcategory_id') === (int) $subcategory->id)>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="create_base_sku">Base SKU (optional)</label>
                <input id="create_base_sku" name="base_sku" value="{{ old('base_sku') }}">
            </div>

            <div class="field">
                <label for="create_is_published">Published</label>
                <input id="create_is_published" name="is_published" type="checkbox" value="1" @checked(old('is_published'))>
            </div>

            <div class="field">
                <label for="create_images">Product Images (multiple)</label>
                <input id="create_images" name="images[]" type="file" multiple accept="image/*">
            </div>

            <h3>Variant (optional)</h3>
            <div class="field">
                <label for="create_variant_sku_0">SKU</label>
                <input id="create_variant_sku_0" name="variant_sku[]" value="{{ old('variant_sku.0') }}">
            </div>

            <div class="field">
                <label for="create_variant_price_0">Price</label>
                <input id="create_variant_price_0" name="variant_price[]" type="number" step="0.01" min="0" value="{{ old('variant_price.0') }}">
            </div>

            <div class="field">
                <label for="create_variant_stock_0">Stock Quantity</label>
                <input id="create_variant_stock_0" name="variant_stock_quantity[]" type="number" min="0" value="{{ old('variant_stock_quantity.0') }}">
            </div>

            <div class="field">
                <label for="create_variant_active_0">Variant Active</label>
                <select id="create_variant_active_0" name="variant_is_active[]">
                    <option value="1" @selected(old('variant_is_active.0', '1') == '1')>Yes</option>
                    <option value="0" @selected(old('variant_is_active.0') == '0')>No</option>
                </select>
            </div>

            <div class="field">
                <label for="create_variant_attr_name_0">Attribute Name</label>
                <input id="create_variant_attr_name_0" name="variant_attribute_name[]" value="{{ old('variant_attribute_name.0') }}">
            </div>

            <div class="field">
                <label for="create_variant_attr_value_0">Attribute Value</label>
                <input id="create_variant_attr_value_0" name="variant_attribute_value[]" value="{{ old('variant_attribute_value.0') }}">
            </div>

            <div class="field" style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px;">
                <p><strong>Additional Variant (optional)</strong></p>
                <label for="create_variant_sku_1">SKU</label>
                <input id="create_variant_sku_1" name="variant_sku[]" value="{{ old('variant_sku.1') }}">

                <label for="create_variant_price_1">Price</label>
                <input id="create_variant_price_1" name="variant_price[]" type="number" step="0.01" min="0" value="{{ old('variant_price.1') }}">

                <label for="create_variant_stock_1">Stock Quantity</label>
                <input id="create_variant_stock_1" name="variant_stock_quantity[]" type="number" min="0" value="{{ old('variant_stock_quantity.1') }}">

                <label for="create_variant_active_1">Variant Active</label>
                <select id="create_variant_active_1" name="variant_is_active[]">
                    <option value="1" @selected(old('variant_is_active.1', '1') == '1')>Yes</option>
                    <option value="0" @selected(old('variant_is_active.1') == '0')>No</option>
                </select>

                <label for="create_variant_attr_name_1">Attribute Name</label>
                <input id="create_variant_attr_name_1" name="variant_attribute_name[]" value="{{ old('variant_attribute_name.1') }}">

                <label for="create_variant_attr_value_1">Attribute Value</label>
                <input id="create_variant_attr_value_1" name="variant_attribute_value[]" value="{{ old('variant_attribute_value.1') }}">
            </div>

            <button type="submit" class="btn">Create Product</button>
        </form>
    </div>

    @if ($editingProduct)
        <div class="card">
            <h2>Edit Product #{{ $editingProduct->id }}</h2>

            <form method="POST" action="{{ url('/products-crud/'.$editingProduct->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="edit_name">Name</label>
                    <input id="edit_name" name="name" value="{{ old('name', $editingProduct->name) }}" required>
                </div>

                <div class="field">
                    <label for="edit_slug">Slug (optional)</label>
                    <input id="edit_slug" name="slug" value="{{ old('slug', $editingProduct->slug) }}">
                </div>

                <div class="field">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description" rows="3">{{ old('description', $editingProduct->description) }}</textarea>
                </div>

                <div class="field">
                    <label for="edit_category_id">Category</label>
                    <select id="edit_category_id" name="category_id">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) old('category_id', $editingProduct->category_id) === (int) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="edit_subcategory_id">Subcategory</label>
                    <select id="edit_subcategory_id" name="subcategory_id">
                        <option value="">Select subcategory</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" @selected((int) old('subcategory_id', $editingProduct->subcategory_id) === (int) $subcategory->id)>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="edit_base_sku">Base SKU (optional)</label>
                    <input id="edit_base_sku" name="base_sku" value="{{ old('base_sku', $editingProduct->base_sku) }}">
                </div>

                <div class="field">
                    <label for="edit_is_published">Published</label>
                    <input id="edit_is_published" name="is_published" type="checkbox" value="1" @checked(old('is_published', (int) $editingProduct->is_published))>
                </div>

                <div class="field">
                    <label for="edit_images">Upload Additional Images</label>
                    <input id="edit_images" name="images[]" type="file" multiple accept="image/*">
                </div>

                @if ($editingImages->isNotEmpty())
                    <h3>Existing Images</h3>
                    @foreach ($editingImages as $image)
                        <div class="field" style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px;">
                            <p><strong>Path:</strong> {{ $image->file_path }}</p>
                            <label>
                                <input type="radio" name="primary_image_id" value="{{ $image->id }}" @checked((int) old('primary_image_id', $editingProduct->product_image_id) === (int) $image->id)>
                                Set as Primary
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="delete_image_ids[]" value="{{ $image->id }}">
                                Delete this image
                            </label>
                        </div>
                    @endforeach
                @endif

                <h3>Variants</h3>
                @php
                    $variantRows = $editingVariants->count() > 0
                        ? $editingVariants->push((object) ['sku' => '', 'price' => '', 'stock_quantity' => '', 'is_active' => 1, 'attribute_name' => '', 'attribute_value' => ''])
                        : collect([(object) ['sku' => '', 'price' => '', 'stock_quantity' => '', 'is_active' => 1, 'attribute_name' => '', 'attribute_value' => '']]);
                @endphp

                @foreach ($variantRows as $index => $variant)
                    <div class="field" style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px;">
                        <p><strong>Variant {{ $index + 1 }}</strong></p>
                        <label>SKU</label>
                        <input name="variant_sku[]" value="{{ old('variant_sku.'.$index, $variant->sku) }}">

                        <label>Price</label>
                        <input name="variant_price[]" type="number" step="0.01" min="0" value="{{ old('variant_price.'.$index, $variant->price) }}">

                        <label>Stock Quantity</label>
                        <input name="variant_stock_quantity[]" type="number" min="0" value="{{ old('variant_stock_quantity.'.$index, $variant->stock_quantity) }}">

                        <label>Variant Active</label>
                        <select name="variant_is_active[]">
                            <option value="1" @selected((string) old('variant_is_active.'.$index, (string) (int) $variant->is_active) === '1')>Yes</option>
                            <option value="0" @selected((string) old('variant_is_active.'.$index, (string) (int) $variant->is_active) === '0')>No</option>
                        </select>

                        <label>Attribute Name</label>
                        <input name="variant_attribute_name[]" value="{{ old('variant_attribute_name.'.$index, $variant->attribute_name) }}">

                        <label>Attribute Value</label>
                        <input name="variant_attribute_value[]" value="{{ old('variant_attribute_value.'.$index, $variant->attribute_value) }}">
                    </div>
                @endforeach

                <button type="submit" class="btn">Update Product</button>
            </form>

            <form method="POST" action="{{ url('/products-crud/'.$editingProduct->id) }}" style="margin-top: 10px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn secondary" onclick="return confirm('Delete this product?')">Delete Product</button>
            </form>
        </div>
    @endif

    <div class="card">
        <h2>Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Published</th>
                    <th>Primary Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->slug }}</td>
                        <td>{{ $product->category_name ?? '-' }}</td>
                        <td>{{ $product->subcategory_name ?? '-' }}</td>
                        <td>{{ (int) $product->is_published === 1 ? 'Yes' : 'No' }}</td>
                        <td>{{ $product->primary_image_path ?? '-' }}</td>
                        <td>
                            <a class="btn secondary" href="{{ url('/products-crud?edit_product_id='.$product->id) }}">Edit</a>
                            <form method="POST" action="{{ url('/products-crud/'.$product->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn secondary" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection
