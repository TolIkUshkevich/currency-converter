<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Конвертер валют</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <header>
        <h1><span class="header-text">Конвертер валют</span></h1>
        <button class="main-page-link" data-route="{{ route('main.page') }}">
            Главная
        </button>
        <button class="course-graphics-link" data-route="{{ route('select.page') }}">
            График курса валют
        </button>
    </header>

    <div id="chart" class="chart"></div>

    

    <script>
        // Ждем загрузки DOM
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                chart: {
                    type: 'line',
                    height: 400 // добавляем высоту
                },
                series: [{
                    name: 'sales',
                    data: @json(array_values($currencyPairValues))
                }],
                xaxis: {
                    categories: @json(array_keys($currencyPairValues))
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
</body>
</html>