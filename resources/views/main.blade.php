<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Конвертер валют</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" />
    <style>
        :root {
            --primary-color: #2a5bd7;
            --border-color: #d1d5db;
            --hover-bg: #f3f4f6;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px auto;
            padding: 0 20px;
            color: #333;
            display: flex;
            justify-content: center;
            background-color: #f8f9fa;
        }
        
        .converter {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 900px; /* Увеличенная ширина */
        }
        
        .converter-header {
            background: var(--primary-color);
            color: white;
            padding: 20px;
            font-size: 1.3rem;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        
        .input-group {
            display: flex;
            align-items: center;
            padding: 25px; /* Увеличенный отступ */
            border-bottom: 1px solid var(--border-color);
        }
        
        .input-group:last-child {
            border-bottom: none;
        }
        
        .currency-input {
            flex: 1;
            padding: 15px; /* Увеличенный отступ */
            font-size: 1.1rem; /* Увеличенный размер шрифта */
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-align: right;
            font-weight: 500;
            transition: border-color 0.2s;
            height: 54px; /* Фиксированная высота */
            box-sizing: border-box;
        }
        
        .currency-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(42, 91, 215, 0.2);
        }
        
        .currency-select-container {
            position: relative;
            min-width: 250px; /* Увеличена ширина */
            margin-left: 20px; /* Увеличен отступ */
        }
        
        .swap-container {
            display: flex;
            justify-content: center;
            padding: 15px 0; /* Увеличен отступ */
            background: #f9fafb;
            border-bottom: 1px solid var(--border-color);
        }
        
        .swap-btn {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 50px; /* Увеличен размер */
            height: 50px; /* Увеличен размер */
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: var(--shadow);
        }
        
        .swap-btn:hover {
            background: var(--hover-bg);
            transform: rotate(180deg);
        }
        
        .swap-btn svg {
            width: 24px;
            height: 24px;
        }
        
        .frequent-currencies {
            padding: 20px; /* Увеличен отступ */
            background: #f9fafb;
        }
        
        .frequent-title {
            font-size: 16px; /* Увеличен размер */
            color: #666;
            margin-bottom: 15px; /* Увеличен отступ */
            font-weight: 500;
        }
        
        .frequent-list {
            display: flex;
            gap: 8px; /* Увеличен отступ */
            flex-wrap: wrap;
        }
        
        .frequent-currency {
            display: flex;
            align-items: center;
            padding: 8px 15px; /* Увеличен отступ */
            background: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }
        
        .frequent-currency:hover {
            background: var(--hover-bg);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .frequent-flag {
            width: 24px; /* Увеличен размер */
            height: 18px; /* Увеличен размер */
            margin-right: 8px; /* Увеличен отступ */
            border-radius: 2px;
            background-size: cover;
            background-position: center;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        }
        
        .converter-footer {
            padding: 20px; /* Увеличен отступ */
            background: white;
            border-top: 1px solid var(--border-color);
            font-size: 15px; /* Увеличен размер */
            color: #666;
            text-align: center;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* Стили для кастомного dropdown */
        .custom-select {
            position: relative;
            width: 100%;
            font-family: Arial, sans-serif;
        }

        .select-selected {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px; /* Увеличен отступ */
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            height: 54px; /* Фиксированная высота */
            box-sizing: border-box;
        }

        .select-selected:hover {
            background-color: var(--hover-bg);
            border-color: #ccc;
        }

        .select-selected.select-arrow-active {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            border-color: #aaa;
        }

        .select-selected:after {
            content: "";
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid #666;
            transition: transform 0.3s;
            margin-left: 12px; /* Увеличен отступ */
        }

        .select-selected.select-arrow-active:after {
            transform: rotate(180deg);
        }

        /* Выпадающий список выходит за пределы формы */
        .select-items {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 8px 8px;
            z-index: 1000;
            max-height: 350px;
            overflow-y: auto;
            box-shadow: var(--shadow);
            display: none;
        }

        .select-items.select-open {
            display: block;
        }

        .select-item {
            display: flex;
            align-items: center;
            padding: 15px; /* Увеличен отступ */
            cursor: pointer;
            transition: background 0.2s;
        }

        .select-item:hover {
            background: var(--hover-bg);
        }

        .select-item.selected {
            background-color: #e6f0ff;
            font-weight: 600;
        }

        .currency-flag {
            margin-right: 15px; /* Увеличен отступ */
            border-radius: 3px;
            background-size: cover;
            background-position: center;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        }

        .currency-code {
            font-weight: 600;
            font-size: 17px; /* Увеличен размер */
            flex-grow: 1;
        }
        
        /* Скрытие оригинального select */
        .original-select {
            display: none;
        }
    </style>
</head>
<body>
    <div class="converter">
        <div class="converter-header">
            Конвертер валют
        </div>
        
        <!-- Скрытые оригинальные select элементы -->
        <select name="from_currency" id="from_currency" class="original-select">
            @foreach($currencies as $currency)
            <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'USD' ? 'selected' : '' }}>
                {{ $currency['char_code'] }}
            </option>
            @endforeach
        </select>
        
        <select name="to_currency" id="to_currency" class="original-select">
            @foreach($currencies as $currency)
            <option value="{{ $currency['char_code'] }}" {{ $currency['char_code'] == 'EUR' ? 'selected' : '' }}>
                {{ $currency['char_code'] }}
            </option>
            @endforeach
        </select>
        
        <div class="input-group">
            <input type="text" class="currency-input" id="amount1" value="1" autocomplete="off" placeholder="0.00">
            <div class="currency-select-container">
                <!-- Кастомный dropdown для первой валюты -->
                <div class="custom-select" id="currency1-select">
                    <div class="select-selected">
                        <div class="currency-flag" id="currency1-flag" style="background-image: url('/flags/usd.svg')"></div>
                        <div class="currency-code" id="currency1-code">USD</div>
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
                        <div class="currency-flag" id="currency2-flag" style="background-image: url('/flags/eur.svg')"></div>
                        <div class="currency-code" id="currency2-code">EUR</div>
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
    <script>import "flag-icons/css/flag-icons.min.css";</script>
    <script>
    // Состояние приложения
    const state = {
        currency1: 'USD',
        currency2: 'EUR',
        activeField: 'amount1' // Отслеживаем активное поле ввода
    };

    // Флаг для блокировки рекурсивных вызовов
    let programmaticUpdate = false;

    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        initCustomDropdowns();
        initEventListeners();
        convertCurrency();
        markSelectedOptions();
    });

    // Инициализация кастомных dropdown
    function initCustomDropdowns() {
        const customSelects = document.querySelectorAll('.custom-select');
        
        customSelects.forEach(select => {
            const selected = select.querySelector('.select-selected');
            const itemsContainer = select.querySelector('.select-items');
            const items = itemsContainer.querySelectorAll('.select-item');
            
            // Обработчик клика на выбранном элементе
            selected.addEventListener('click', (e) => {
                // Закрыть другие открытые списки
                document.querySelectorAll('.select-items').forEach(container => {
                    if (container !== itemsContainer) {
                        container.classList.remove('select-open');
                        container.closest('.custom-select').querySelector('.select-selected').classList.remove('select-arrow-active');
                    }
                });
                
                // Переключить текущий список
                itemsContainer.classList.toggle('select-open');
                selected.classList.toggle('select-arrow-active');
                e.stopPropagation();
            });
            
            // Обработчики для элементов списка
            items.forEach(item => {
                item.addEventListener('click', () => {
                    // Определяем тип валюты (1 или 2)
                    const currencyType = select.id.includes('1') ? '1' : '2';
                    const currency = item.getAttribute('data-currency');
                    const flag = item.getAttribute('data-flag');
                    
                    // Обновляем состояние
                    state[`currency${currencyType}`] = currency;
                    
                    // Обновляем отображение выбранной валюты
                    updateCurrencyDisplay(currencyType, currency, flag);
                    
                    // Обновляем оригинальный select
                    document.getElementById(`${currencyType === '1' ? 'from_currency' : 'to_currency'}`).value = currency;
                    
                    // Помечаем выбранную опцию
                    items.forEach(i => i.classList.remove('selected'));
                    item.classList.add('selected');
                    
                    // Закрываем список
                    itemsContainer.classList.remove('select-open');
                    selected.classList.remove('select-arrow-active');
                    
                    // Конвертируем валюту
                    convertCurrency();
                    
                    // Сохраняем выбор в сессии
                    saveCurrencySelection(`currency${currencyType}`, currency);
                });
            });
        });
        
        // Закрытие выпадающего списка при клике вне его
        document.addEventListener('click', () => {
            document.querySelectorAll('.select-items').forEach(container => {
                container.classList.remove('select-open');
            });
            document.querySelectorAll('.select-selected').forEach(selected => {
                selected.classList.remove('select-arrow-active');
            });
        });
        
        // Блокировка закрытия при клике внутри списка
        document.querySelectorAll('.select-items').forEach(container => {
            container.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    }

    // Помечаем выбранные опции при загрузке
    function markSelectedOptions() {
        markSelectedOption('1', state.currency1);
        markSelectedOption('2', state.currency2);
    }
    
    function markSelectedOption(currencyType, currency) {
        const dropdown = document.getElementById(`currency${currencyType}-dropdown`);
        if (dropdown) {
            dropdown.querySelectorAll('.select-item').forEach(item => {
                if (item.getAttribute('data-currency') === currency) {
                    item.classList.add('selected');
                }
            });
        }
    }

    // Инициализация обработчиков событий
    function initEventListeners() {
        // Кнопка обмена валют
        document.getElementById('swapBtn').addEventListener('click', swapCurrencies);
        
        // Ввод суммы в первом поле
        document.getElementById('amount1').addEventListener('input', function() {
            if (programmaticUpdate) return;
            state.activeField = 'amount1';
            convertCurrency();
        });
        
        // Ввод суммы во втором поле
        document.getElementById('amount2').addEventListener('input', function() {
            if (programmaticUpdate) return;
            state.activeField = 'amount2';
            convertCurrency();
        });
        
        // Часто используемые валюты
        document.querySelectorAll('.frequent-currency').forEach(item => {
            item.addEventListener('click', function() {
                state.currency2 = this.dataset.currency;
                updateCurrencyDisplay('2', state.currency2);
                // Обновляем оригинальный select
                document.getElementById('to_currency').value = state.currency2;
                markSelectedOption('2', state.currency2);
                convertCurrency();
            });
        });
    }

    // Обновление отображения валюты
    function updateCurrencyDisplay(currencyType, currency, flag = null) {
        const prefix = `currency${currencyType}`;
        const flagElement = document.getElementById(`${prefix}-flag`);
        const codeElement = document.getElementById(`${prefix}-code`);
        
        if (!flag) {
            flag = `${currency.toLowerCase()}.svg`;
        }
        
        flagElement.style.backgroundImage = `url('/flags/${flag}')`;
        codeElement.textContent = currency;
    }

    // Упрощенный обмен валют
    function swapCurrencies() {
        // Сохраняем текущее значение первого поля
        const amount1Value = document.getElementById('amount1').value;
        
        // Меняем валюты местами в состоянии
        [state.currency1, state.currency2] = [state.currency2, state.currency1];
        
        // Обновляем отображение
        updateCurrencyDisplay('1', state.currency1);
        updateCurrencyDisplay('2', state.currency2);
        
        // Обновляем оригинальные select
        document.getElementById('from_currency').value = state.currency1;
        document.getElementById('to_currency').value = state.currency2;
        
        // Обновляем выделенные опции
        markSelectedOption('1', state.currency1);
        markSelectedOption('2', state.currency2);
        
        // Восстанавливаем значение первого поля
        programmaticUpdate = true;
        document.getElementById('amount1').value = amount1Value;
        programmaticUpdate = false;
        
        // Устанавливаем активное поле как первое
        state.activeField = 'amount1';
        
        // Конвертируем валюту
        convertCurrency();
    }

    // Конвертация валюты
    function convertCurrency() {
        let fromCurrency, toCurrency;
        const amount1Input = document.getElementById('amount1');
        const amount2Input = document.getElementById('amount2');
        const rateInfo = document.getElementById('rate-info');
        
        // Определяем направление конвертации
        if (state.activeField === 'amount1') {
            fromCurrency = state.currency1;
            toCurrency = state.currency2;
        } else {
            fromCurrency = state.currency2;
            toCurrency = state.currency1;
        }

        // Получаем значение из активного поля
        const amount = parseFloat(
            state.activeField === 'amount1' ? amount1Input.value : amount2Input.value
        ) || 0;

        if (!amount) {
            programmaticUpdate = true;
            amount1Input.value = '';
            amount2Input.value = '';
            programmaticUpdate = false;
            rateInfo.textContent = 'Введите сумму';
            return;
        }

        fetch('/convert', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                fromCharCode: fromCurrency,
                toCharCode: toCurrency,
                amount: amount
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                programmaticUpdate = true;
                
                if (state.activeField === 'amount1') {
                    amount2Input.value = data.result;
                } else {
                    amount1Input.value = data.result;
                }
                
                programmaticUpdate = false;
                rateInfo.textContent = `1 ${fromCurrency} = ${data.rate} ${toCurrency}`;
            } else {
                console.error('Conversion error:', data.message);
                rateInfo.textContent = data.message || 'Ошибка конвертации';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            rateInfo.textContent = 'Ошибка соединения';
        });
    }

    // Сохранение выбора валюты в сессии
    function saveCurrencySelection(type, currency) {
        fetch('/save-currency', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                type: type,
                currency: currency
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Валюта сохранена:', currency);
            }
        });
    }
</script>
</body>
</html>