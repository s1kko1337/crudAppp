@extends('layout')

@section('content')
<div class="container">
    <h2>Продажа товаров</h2>
    <form method="POST" action="{{ route('user.sales.store') }}">
        @csrf
        <div id="product-container">
            <div class="form-group product-item">
                <label for="product">Выберите товар:</label>
                <select class="form-control" name="products[0][product_id]">
                    @foreach($products as $product)
                        <option value="{{ $product->id_product }}">{{ $product->name_product }} (Доступно: {{ $product->quantity_products }})</option>
                    @endforeach
                </select>
                <label for="quantity">Количество:</label>
                <input type="number" class="form-control" name="products[0][quantity]" min="1" required>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" id="add-product">Добавить еще товар</button>
        <div class="form-group">
            <label for="sale_date">Дата продажи:</label>
            <input type="date" class="form-control" id="sale_date" name="sale_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Продать</button>
    </form>
</div>

<script>
document.getElementById('add-product').addEventListener('click', function() {
    var container = document.getElementById('product-container');
    var index = container.children.length;
    var newProduct = `
        <div class="form-group product-item">
            <label for="product">Выберите товар:</label>
            <select class="form-control" name="products[${index}][product_id]">
                @foreach($products as $product)
                    <option value="{{ $product->id_product }}">{{ $product->name_product }} (Доступно: {{ $product->quantity_products }})</option>
                @endforeach
            </select>
            <label for="quantity">Количество:</label>
            <input type="number" class="form-control" name="products[${index}][quantity]" min="1" required>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newProduct);
});
</script>
@endsection
