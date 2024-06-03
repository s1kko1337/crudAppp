@extends('layout')

@section('content')
    @parent
    @if ($tableName != 'supply_detail' && $tableName != 'sale_details')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Редактирование данных таблицы "{{ strtolower($tableNamesTranslated[$tableName]) }}"</h1>
    </div>

    <div class="table-responsive small">
        <table class="table table-sm">
            @if($tableName != 'sales' && $tableName != 'supplies' && $tableName != 'storage')
            <thead>
                <tr>
                    <th>#</th>
                    @foreach ($editableColumns as $column)
                    <th>{{ $columnNames[$tableName][$column] }}</th>
                    @endforeach
                    <th>Действия</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <form action="{{ route('user.tables.update', ['tableName' => $tableName, 'id' => $row->$tableId ]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @foreach ($editableColumns as $column)
                                <td>
                                    <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" value="{{ $row->$column }}" required>
                                </td>
                            @endforeach 
                            <td class="inline" style="display: flex;">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </td> 
                        </form>
                        <td>
                            <form action="{{ route('user.tables.delete', ['tableName' => $tableName, 'id' => $row->$tableId]) }}" method="POST" style="margin-left: 10px;">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </td>
                        @if ($tableName == 'sales')
                            <td>
                                <a href="#" class="show-details" data-id="{{ $row->id_sale }}" data-type="sales">Подробности о продаже</a>
                            </td>
                        @endif
                        @if ($tableName == 'supplies')
                            <td>
                                <a href="#" class="show-details" data-id="{{ $row->id_supply }}" data-type="supplies">Подробности о поставке</a>
                            </td>
                        @endif
                    </tr>
                    <tr class="details-row" id="details-{{ $row->$tableId }}" style="display:none;">
                        <td colspan="100">
                            <div class="details-content"></div>
                        </td>
                    </tr>
                @endforeach
                @if ($tableName !== 'users' && $tableName !== 'sales' && $tableName !== 'sale_details' && $tableName !== 'supplies_status' && $tableName !== 'supplies' && $tableName !== 'storage')
                <tr>
                    <td>#</td>
                    <form action="{{ route('user.tables.add', ['tableName' => $tableName]) }}" method="POST">
                        @csrf
                        @foreach ($editableColumns as $column)
                            <td>
                                <input type="text" class="form-control" name="{{ $column }}" placeholder="{{ $columnNames[$tableName][$column] }}" required>
                            </td>
                        @endforeach
                        <td class="inline" style="display: flex;">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </td>
                    </form>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

        @if ($tableName == 'sales' || $tableName == 'supplies')
            <thead>
                <tr>
                    <th>#</th>
                    @foreach ($editableColumns as $column)
                    <th>{{ $columnNames[$tableName][$column] }}</th>
                    @endforeach
                    <th>Действия</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <form action="{{ route('user.tables.update', ['tableName' => $tableName, 'id' => $row->$tableId ]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @foreach ($editableColumns as $column)
                                <td>
                                    @if($column != 'id_sale' && $column != 'id_supply')
                                    <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" value="{{ $row->$column }}" required>
                                    @endif
                                    @if ($column == 'id_sale' || $column == 'id_supply')
                                        <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" value="{{ $row->$column }}" required>
                                        <a href="#" class="show-details" data-id="{{ $row->$column }}" data-type="{{ $tableName == 'sales' ? 'sales' : 'supplies' }}">Подробности о {{ $tableName == 'sales' ? 'продаже' : 'поставке' }}</a>
                                    @endif
                                </td>
                            @endforeach 
                            <td class="inline" style="display: flex;">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </td> 
                        </form>
                        <td>
                            <form action="{{ route('user.tables.delete', ['tableName' => $tableName, 'id' => $row->$tableId]) }}" method="POST" style="margin-left: 10px;">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>   
                    <tr class="details-row" id="details-{{ $row->$tableId }}" style="display:none;">
                        <td colspan="100">
                            <div class="details-content"></div>
                        </td>
                    </tr>
                @endforeach
                @if ($tableName !== 'users' && $tableName !== 'sales' && $tableName !== 'sale_details' && $tableName !== 'supplies_status' && $tableName !== 'supplies' && $tableName !== 'storage')
                <tr>
                    <td>#</td>
                    <form action="{{ route('user.tables.add', ['tableName' => $tableName]) }}" method="POST">
                        @csrf
                        @foreach ($editableColumns as $column)
                            <td>
                                <input type="text" class="form-control" name="{{ $column }}" placeholder="{{ $columnNames[$tableName][$column] }}" required>
                            </td>
                        @endforeach
                        <td class="inline" style="display: flex;">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </td>
                    </form>
                </tr>
                @endif
            </tbody>
        @endif

        @if ($tableName == 'storage')
            <thead>
                <tr>
                    <th>#</th>
                    @foreach ($editableColumns as $column)
                    <th>{{ $columnNames[$tableName][$column] }}</th>
                    @endforeach
                    <th>Действия</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <form action="{{ route('user.tables.update', ['tableName' => $tableName, 'id' => $row->$tableId ]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @foreach ($editableColumns as $column)
                                <td>
                                    @if($column != 'id_product')
                                    <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" value="{{ $row->$column }}" required>
                                    @endif
                                    @if ($column == 'id_product')
                                        <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" value="{{ $row->$column }}" required>
                                        <a href="#" class="show-details" data-id="{{ $row->$column }}" data-type="product">Подробнее о товаре</a>
                                    @endif
                                </td>
                            @endforeach 
                            <td class="inline" style="display: flex;">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </td> 
                        </form>
                        <td>
                            <form action="{{ route('user.tables.delete', ['tableName' => $tableName, 'id' => $row->$tableId]) }}" method="POST" style="margin-left: 10px;">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>   
                    <tr class="details-row" id="details-{{ $row->$tableId }}" style="display:none;">
                        <td colspan="100">
                            <div class="details-content"></div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif

        @endif
    </div>
@yield('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.show-details').forEach(function (element) {
            element.addEventListener('click', function (event) {
                event.preventDefault();
                const id = event.target.dataset.id;
                const type = event.target.dataset.type;
                const detailsRow = document.getElementById(`details-${id}`);
                const detailsContent = detailsRow.querySelector('.details-content');
                
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    fetch(`/${type}/${id}/details`)
                        .then(response => response.json())
                        .then(data => {
                            detailsContent.innerHTML = '';
                            if (type === 'sales') {
                                detailsContent.innerHTML = formatSalesDetails(data);
                            } else if (type === 'supplies') {
                                detailsContent.innerHTML = formatSuppliesDetails(data);
                            } else if (type === 'product') {
                                detailsContent.innerHTML = formatProductDetails(data);
                            }
                            detailsRow.style.display = 'table-row';
                        });
                } else {
                    detailsRow.style.display = 'none';
                }
            });
        });

        function formatSalesDetails(data) {
            let html = '<table class="table table-sm table-bordered">';
            html += '<thead><tr><th>ID продажи</th><th>Название товара</th><th>Количество</th><th>Дата продажи</th><th>Имя продавца</th><th>Общая цена</th></tr></thead><tbody>';
            data.forEach(item => {
                html += `<tr>
                    <td>${item.id_sale}</td>
                    <td>${item.name_product}</td>
                    <td>${item.quantity}</td>
                    <td>${item.sale_date}</td>
                    <td>${item.name_saler}</td>
                    <td>${item.total_price}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            return html;
        }

        function formatSuppliesDetails(data) {
            let html = '<table class="table table-sm table-bordered">';
            html += '<thead><tr><th>ID поставки</th><th>Имя поставщика</th><th>Дата поставки</th><th>Название товара</th><th>Количество товара</th><th>Общая цена</th></tr></thead><tbody>';
            data.forEach(item => {
                html += `<tr>
                    <td>${item.id_supply}</td>
                    <td>${item.name_supplier}</td>
                    <td>${item.supply_date}</td>
                    <td>${item.name_product}</td>
                    <td>${item.quantity}</td>
                    <td>${item.total_price}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            return html;
        }

        function formatProductDetails(data) {
            let html = '<table class="table table-sm table-bordered">';
            html += '<thead><tr><th>Название товара</th><th>Цена товара</th></tr></thead><tbody>';
            html += `<tr>
                <td>${data.name_product}</td>
                <td>${data.price_product}</td>
            </tr>`;
            html += '</tbody></table>';
            return html;
        }
    });
</script>
@endsection

