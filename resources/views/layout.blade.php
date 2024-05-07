<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
            <div class="dropdown mb-3">
            <button class="btn btn-light btn-secondary dropdown-toggle w-100 text-truncate" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg><i class="bi bi-person"></i>
                    @auth
                        {{ Auth::user()->username }} 
                    @endauth
            </button>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="/profile">Настройки профиля</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('user.logout') }}"">Выйти</a></li>
                </ul>
            </div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('user.home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                        Главная
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.tables') }}" class="nav-link {{ request()->routeIs('tables') ? 'active' : '' }}"">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                        Таблицы
                    </a>
                </li>
            </ul>
        </div>
        
        <main class="col-md-9 col-lg-10 vh-100">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownToggle = document.querySelector('.dropdown-toggle');
        var emailText = document.querySelector('.email-text');

        dropdownToggle.addEventListener('click', function(event) {
            event.preventDefault();
            var dropdownMenu = this.nextElementSibling;
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
                dropdownMenu.removeAttribute('style');
                emailText.classList.add('text-truncate');
            } else {
                dropdownMenu.classList.add('show');
                dropdownMenu.style.display = 'block';
                emailText.classList.remove('text-truncate');
            }
        });

        document.addEventListener('click', function(event) {
            var dropdownMenus = document.querySelectorAll('.dropdown-menu');
            dropdownMenus.forEach(function(menu) {
                if (!menu.contains(event.target) && !event.target.classList.contains('dropdown-toggle')) {
                    menu.classList.remove('show');
                    menu.removeAttribute('style');
                    emailText.classList.add('text-truncate');
                }
            });
        });
    });
</script>

</body>
</html>