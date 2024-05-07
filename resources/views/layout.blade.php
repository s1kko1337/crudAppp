<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
            <div class="dropdown mb-3">
                <button class="btn btn-light btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    Username
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="/profile">Профиль</a></li>
                    <li><a class="dropdown-item" href="/settings">Настройки</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/">Выйти</a></li>
                </ul>
            </div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                        Главная
                    </a>
                </li>
                <li>
                    <a href="{{ route('tables') }}" class="nav-link {{ request()->routeIs('tables') ? 'active' : '' }}"">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                        Таблицы
                    </a>
                </li>
            </ul>
        </div>
        
        <main class="col-md-9 col-lg-10">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(event) {
                event.preventDefault();
                var dropdownMenu = this.nextElementSibling;
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownMenu.removeAttribute('style');
                } else {
                    dropdownMenu.classList.add('show');
                    dropdownMenu.style.display = 'block';
                }
            });
        });

        document.addEventListener('click', function(event) {
            var dropdownMenus = document.querySelectorAll('.dropdown-menu');
            dropdownMenus.forEach(function(menu) {
                if (!menu.contains(event.target) && !event.target.classList.contains('dropdown-toggle')) {
                    menu.classList.remove('show');
                    menu.removeAttribute('style');
                }
            });
        });
    });
</script>

</body>
</html>