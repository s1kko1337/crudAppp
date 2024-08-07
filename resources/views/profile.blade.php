@extends('layout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<div class="container mt-5" style="overflow-y: auto;">
  <h2>Управление профилем</h2>
  <table class="table">
    <tr>
      <th>Имя</th>
      <td>@auth {{Auth::user()->username}}@endauth</td>
    </tr>
    <tr>
      <th>Адрес электронной почты</th>
      <td>@auth {{Auth::user()->email}}@endauth</td>
    </tr>
    <th>Должность</th>
      <td>@php
            $role = (new \App\Http\Controllers\ProfileController())->getRole();
            echo $role;
        @endphp
        </td>
    </tr>
  </table>
  {{-- {{action="{{ route('user.register')}}"--}}
  <form method="POST" action="{{ route('user.profile.editName') }}">
    @csrf
    <div class="form-group my-1">
      <label for="username">Изменить имя и фамилию:</label>
      <input type="text" class="form-control" id="username" name="username" placeholder="Введите новое имя пользователя">
    </div>
    <button type="submit" class="btn btn-primary">Изменить</button>
  </form>
  <form method="POST" action="{{ route('user.profile.editEmail') }}">
    @csrf
    <div class="form-group my-1">
    <label for="email">Эл.почта</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
    </div>
    <button type="submit" class="btn btn-primary">Изменить</button>
  </form>
  @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
</div>
@endsection
