@extends('admin')

@section('content')
@parent 
    <main class="container mt-10">
    <hr>
    <div class="row">
        <div class="col">
            <h2>Редактировать пользователя</h2>
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="username" class="form-label">Имя пользователя</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Эл. почта</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="form-floating my-2">
                <select class="form-select" id="role" name="roleId">
                  <option value="0">Администратор</option>
                  <option value="1">Главный менеджер</option>
                  <option value="2">Мерчендайзер</option>
                  </select>
                  <label class="form-label bg-transparent border-0" for="role">Роль</label>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
    </main>
@endsection
