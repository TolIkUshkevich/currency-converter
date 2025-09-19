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
        <button class="main-page-link header-text" data-route="{{ route('main.page') }}">
        Конвертер валют
        </button>
        <button class="course-graphics-link" data-route="{{ route('select.page') }}">
            График курса валют
        </button>
    </header>
    <div>{{ $numerator }} to {{ $denumerator }} graphic</div>
    <div id="chart" class="chart"></div>
    <div class="period-buttons">
        <button id="week-btn" class="active-period">Неделя</button>
        <button id="month-btn">Месяц</button>
        <button id="year-btn">Год</button>
    </div>
    <script>
          window.currencyData = @json($currencyPairValues);
    </script>
</body>
</html>