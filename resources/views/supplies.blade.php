@extends('layout')

@section('content')
<div class="container">
    <h2>Добавить поставку</h2>
    <form method="POST" action="{{ route('user.store.supply') }}">
        @csrf
        <div class="mb-3">
            <label for="supplier" class="form-label">Выберите поставщика:</label>
            <select class="form-select" name="supplier_id" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id_supplier }}">{{ $supplier->name_org }}</option>
                @endforeach
            </select>
        </div>
        <div id="products-container">
            <div class="product-item mb-3">
                <label for="product" class="form-label">Выберите товар:</label>
                <select class="form-select" name="products[0][product_id]" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id_product }}">{{ $product->name_product }}</option>
                    @endforeach
                </select>
                <label for="quantity" class="form-label">Количество:</label>
                <input type="number" class="form-control" name="products[0][quantity]" min="1" required>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" id="add-product">Добавить еще товар</button>
        <div class="mb-3">
            <label for="supply_date" class="form-label">Дата поставки:</label>
            <input type="date" class="form-control" name="supply_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить поставку</button>
    </form>
</div>

<script>
document.getElementById('add-product').addEventListener('click', function() {
    var container = document.getElementById('products-container');
    var index = container.children.length;
    var newProduct = `
        <div class="product-item mb-3">
            <label for="product" class="form-label">Выберите товар:</label>
            <select class="form-select" name="products[${index}][product_id]" required>
                @foreach($products as $product)
                    <option value="{{ $product->id_product }}">{{ $product->name_product }}</option>
                @endforeach
            </select>
            <label for="quantity" class="form-label">Количество:</label>
            <input type="number" class="form-control" name="products[${index}][quantity]" min="1" required>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newProduct);
});
</script>
@endsection
