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
        <button class="main-page-link header-text" data-route="{{ route('main.page') }}">
        Конвертер валют
        </button>
        <button class="course-graphics-link" data-route="{{ route('select.page') }}">
            График курса валют
        </button>
    </header>
    <div class="converter">
        
        <!-- Скрытые оригинальные select элементы -->
        <select name="from_currency" id="from_currency" class="original-select" style="display: none;">
            @foreach($currencies as $currency)
            <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'USD' ? 'selected' : '' }}>
                {{ $currency['char_code'] }}
            </option>
            @endforeach
        </select>
        
        <select name="to_currency" id="to_currency" class="original-select" style="display: none;">
            @foreach($currencies as $currency)
            <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'EUR' ? 'selected' : '' }}>
                {{ $currency['char_code'] }}
            </option>
            @endforeach
        </select>
        
        <div class="input-group">
            <input type="text" class="currency-input" id="amount1" value="1" autocomplete="off" placeholder="0.00">
            <div class="currency-select-container">
                <div class="custom-select" id="currency1-select">
                    <div class="select-selected">
                        <span class="currency-flag fi fi-{{ config('currencies')['USD'] }}"></span>
                        <div class="currency-code" id="currency1-code">USD</div>
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
        
        <div class="swap-container">
            <div class="swap-btn" id="swapBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4"></path>
                </svg>
            </div>
        </div>
        
        <div class="input-group">
            <input type="text" class="currency-input" id="amount2" autocomplete="off" placeholder="0.00">
            <div class="currency-select-container">
                <!-- Кастомный dropdown для второй валюты -->
                <div class="custom-select" id="currency2-select">
                    <div class="select-selected">
                        <span class="currency-flag fi fi-{{ config('currencies')['EUR'] }}"></span>
                        <div class="currency-code" id="currency2-code">EUR</div>
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
        
        @if(!empty($frequentCurrencies))
        <div class="frequent-currencies">
            <div class="frequent-title">Часто используемые валюты:</div>
            <div class="frequent-list" id="frequentCurrencies">
                @foreach($frequentCurrencies as $currency)
                <div class="frequent-currency" data-currency="{{ $currency['char_code'] }}">
                <span class="currency-flag fi fi-{{ config('currencies')[$currency['char_code']] }}"></span>
                    {{ $currency['char_code'] }}
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="converter-footer">
            <div id="rate-info">Обновление курсов в реальном времени</div>
        </div>
    </div>
</body>
</html>