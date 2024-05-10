@extends('layout')

@section('content')
    @parent
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Таблицы</h1>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th> 
                    @endforeach
                    <th>Действия</th> <!-- Add a new table header for actions -->
                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @foreach ($columns as $column)
                            <td>{{ $row->$column }}</td> 
                        @endforeach
                        <td>
                            <a href="{{ route('user.tables.edit', ['tableName' => $tableName, 'id' => $row->id]) }}" class="btn btn-primary">Редактировать</a>
                            <form action="{{ route('user.tables.delete', ['tableName' => $tableName, 'id' => $row->id]) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </td> 
                    </tr>
                @endforeach      
            </tbody>
        </table>
    </div>
@endsection