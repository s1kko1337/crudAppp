@extends('layout')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Главная</h1>
    <div class="btn-toolbar mb-2 mb-md-0"></div>
</div>

@if (Auth::user()->roleId != 2)
<div class="row mb-3">
    <div class="col-md-6">
        <form id="sellerForm" action="{{ route('user.seller.stats') }}" method="GET">
            <div class="input-group">
                <select class="form-select" name="seller_ids[]" id="sellerSelect" multiple required>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id_saler }}">{{ $seller->name_saler }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Посмотреть статистику</button>
            </div>
        </form>
    </div>
</div>
@endif

@if ($showCharts)
<div class="row">
    <div class="col-md-6">
        <canvas id="lineChart1" class="chart-canvas" width="400" height="400"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="pieChart" class="chart-canvas" width="400" height="400"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="lineChart2" class="chart-canvas" width="400" height="400"></canvas>
    </div>
    <div class="col-md-6 pt-4">
        <div class="table-responsive small">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Общие продажи</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sellers as $seller)
                        <tr>
                            <td>{{ $seller->id_saler }}</td>
                            <td>{{ $seller->name_saler }}</td>
                            <td>{{ $seller->telephone_saler }}</td>
                            <td>{{ $seller->total_sells }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if ($showCharts)
<script>
    var ctxLine1 = document.getElementById('lineChart1').getContext('2d');
    var lineChart1 = new Chart(ctxLine1, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Продажи',
                data: @json($chartData),
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

    var ctxPie = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: @json($pieLabels),
            datasets: [{
                label: 'Продажи по товарам',
                data: @json($pieValues),
                backgroundColor: @json(array_values($productColors)),
                hoverOffset: 4
            }]
        }
    });

    var colors = [
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(199, 199, 199)',
        'rgb(83, 102, 255)'
    ];

    var lineChartDatasets = @json($lineChartDatasets).map((dataset, index) => ({
        ...dataset,
        borderColor: colors[index % colors.length]
    }));

    var ctxLine2 = document.getElementById('lineChart2').getContext('2d');
    var lineChart2 = new Chart(ctxLine2, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: lineChartDatasets
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
@endif
@endsection
