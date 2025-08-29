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