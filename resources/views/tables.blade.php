@extends('layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Таблицы</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-primary">Добавить</button>
                <button type="button" class="btn btn-primary">Редактировать</button>
                <button type="button" class="btn btn-primary">Удалить</button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Наименование товара</th>
                    <th>Общее количество</th>
                    <th>Количество на складе</th>
                    <th>Количество в торговом зале</th>
                    <th>Дата поставки</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Товар 1</td>
                    <td>100</td>
                    <td>50</td>
                    <td>50</td>
                    <td>2024-03-01</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Товар 2</td>
                    <td>150</td>
                    <td>100</td>
                    <td>50</td>
                    <td>2024-03-02</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection