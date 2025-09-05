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
<form action="{{ route('make.graphic') }}" method="POST">
    @csrf
    <select name="numirator" id="numerator" class="original-select">
        @foreach($currencies as $currency)
        <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'USD' ? 'selected' : '' }}>
            {{ $currency['char_code'] }}
        </option>
        @endforeach
    </select>
            
    <select name="denumirator" id="denumerator" class="original-select">
        @foreach($currencies as $currency)
            <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'EUR' ? 'selected' : '' }}>
                {{ $currency['char_code'] }}
            </option>
        @endforeach
</select>

        <div class="input-group-select">
            <div class="currency-select-container-for-graphics">    
                <!-- Кастомный dropdown для первой валюты -->
                <div class="custom-select" id="currency1-select">
                    <div class="select-selected">
                        <div class="currency-flag" id="currency1-flag" style="background-image: url('/flags/usd.svg')"></div>
                        <div class="currency-code-select" id="currency1-code">USD</div>
                    </div>
                    <div class="select-items" id="currency1-dropdown">
                        @foreach($currencies as $currency)
                        <div class="select-item" 
                             data-currency="{{ $currency['char_code'] }}"
                             data-flag="{{ $currency['char_code'] }}.svg">
                            <span class="currency-flag fi fi-{{ config('currencies')[$currency['char_code']] }}"></span>
                            <div class="currency-code">{{ $currency['char_code'] }}</div>
                            <div style="margin-left: 12px; color: #777; font-size: 15px;">{{ $currency['name'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <h2 class="between-select-text">to</h2>

        <div class="input-group-select">
            <div class="currency-select-container-for-graphics">
                <!-- Кастомный dropdown для второй валюты -->
                <div class="custom-select" id="currency2-select">
                    <div class="select-selected">
                        <div class="currency-flag" id="currency2-flag" style="background-image: url('/flags/eur.svg')"></div>
                        <div class="currency-code-select" id="currency2-code">EUR</div>
                    </div>
                    <div class="select-items" id="currency2-dropdown">
                        @foreach($currencies as $currency)
                        <div class="select-item" 
                             data-currency="{{ $currency['char_code'] }}"
                             data-flag="{{ $currency['char_code'] }}.svg">
                            <span class="currency-flag fi fi-{{ config('currencies')[$currency['char_code']] }}"></span>
                            <div class="currency-code">{{ $currency['char_code'] }}</div>
                            <div style="margin-left: 12px; color: #777; font-size: 15px;">{{ $currency['name'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <button type="submit">Show</button>
        </form>
</body>
</html>