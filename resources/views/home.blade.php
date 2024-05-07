@extends('layout')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Главная</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>

    <canvas class="my-4 w-100" id="myChart" width="400" height="200"></canvas>

    <h2>Заголовок раздела</h2>
    <div class="table-responsive small">
      <table class="table table-striped table-sm">
      </table>
    </div>
@endsection

@section('scripts')
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль'],
                datasets: [{
                    label: 'Продажи',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection