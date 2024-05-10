<!-- editTable.blade.php -->

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
                    <th>Действия</th> 
                </tr>
            </thead>
            <tbody>
                <!--            TODO            -->
                @foreach ($tableData as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @foreach ($columns as $index => $column)
                            @if ($index === 0)
                                <td>{{ $row->$column }}</td> 
                            @else
                                <td>
                                    <input type="text" name="{{ $column }}" value="{{ $row->$column }}">
                                </td>
                            @endif
                        @endforeach
                        <td>
                        <form action="{{ route('user.tables.update', ['tableName' => $tableName, 'id' => $row->id]) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PUT') 
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>

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
