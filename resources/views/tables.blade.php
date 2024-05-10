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
                    <th>Наименование таблицы</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tables as $key => $table)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $table }}</td>
                </tr>      
                @endforeach      
            </tbody>
        </table>
    </div>
@endsection