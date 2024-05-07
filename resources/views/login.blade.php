<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">

<main class="form-signin w-100 m-auto">
  <form method="post" action="/check">
  @csrf
  <h1 class="h2 mb-3 fw-normal">Добро пожаловать</h1>

  <div class="form-floating">
    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
    <label for="email">Эл.почта</label>
  </div>
  <div class="form-floating">
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    <label for="password">Пароль</label>
  </div>

  <div class="form-check text-start my-3">
    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault" name="remember_me">
    <label class="form-check-label" for="flexCheckDefault">
      Запомнить меня
    </label>
  </div>
  <button class="btn btn-primary w-100 py-2" type="submit">Войти</button>

  @if($errors->any())
    <div class="alert alert-danger my-1">
      <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
  @endif


  <p class="mt-3 mb-3 text-body-secondary">© Гигакурсач 2024</p>
</form>

</main>
</body>
</html>