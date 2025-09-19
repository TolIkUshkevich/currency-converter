<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>График курса валют</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <header>
        <button class="main-page-link header-text" data-route="{{ route('main.page') }}">
        Конвертер валют
        </button>
        <button class="course-graphics-link" data-route="{{ route('select.page') }}">
            График курса валют
        </button>
    </header>
    
    <form action="{{ route('make.graphic') }}" method="POST">
        @csrf
        <select name="numerator" id="numerator" class="original-select" style="display: none;">
            @foreach($currencies as $currency)
            <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'USD' ? 'selected' : '' }}>
                {{ $currency['char_code'] }}
            </option>
            @endforeach
        </select>
                
        <select name="denumerator" id="denumerator" class="original-select" style="display: none;">
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
                        <span class="currency-flag fi fi-{{ config('currencies')['USD'] }}"></span>
                        <div class="currency-code-select" id="currency1-code">USD</div>
                    </div>
                    <div class="select-items" id="currency1-dropdown">
                        @foreach($currencies as $currency)
                        <div class="select-item" 
                             data-currency="{{ $currency['char_code'] }}">
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
                        <span class="currency-flag fi fi-{{ config('currencies')['EUR'] }}"></span>
                        <div class="currency-code-select" id="currency2-code">EUR</div>
                    </div>
                    <div class="select-items" id="currency2-dropdown">
                        @foreach($currencies as $currency)
                        <div class="select-item" 
                             data-currency="{{ $currency['char_code'] }}">
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