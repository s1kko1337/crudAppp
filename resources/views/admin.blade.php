@extends('profile')

@section('content')
    @parent 
    <main class="container mt-10">
    <hr>
    <div class="row">
        <div class="col">
            <h2>Список пользователей</h2>
            <table id="users-table" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Эл. почта</th>
                        <th>Должность</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>@php
                            $role = (new \App\Http\Controllers\ProfileController())->getUserRole($user);
                            echo $role;
                            @endphp
                        </td>
                        <td>
                            {{-- <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">Редактировать</a> --}}
                            <a href="#" class="btn btn-primary">Редактировать</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
@yield('script')
<script>
    setInterval(function() {
        $.ajax({
            url: '{{ route("user.admin.get.updated.users") }}',
            type: 'GET',
            success: function(response) {
                $('#users-table tbody').empty();
                
                response.users.forEach(function(user) {
                    var newRow = '<tr>' +
                        '<td>' + user.id + '</td>' +
                        '<td>' + user.username + '</td>' +
                        '<td>' + user.email + '</td>' +
                        '<td>' + user.role_name + '</td>' +
                        '<td>' +
                        '<a href="#" class="btn btn-primary">Редактировать</a>' +
                        '</td>' +
                        '</tr>';
                    $('#users-table tbody').append(newRow);
                });
            }
        });
    }, 5000);
</script>
