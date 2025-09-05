// Состояние приложения
const state = {
    currency1: 'USD',
    currency2: 'EUR'
};

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function() {
    initDropdowns();
    syncWithOriginalSelects();
    markSelectedOptions();
});

// Функция для инициализации всех dropdown
function initDropdowns() {
    // Обработчик для заголовков dropdown (select-selected)
    document.querySelectorAll('.select-selected').forEach(selected => {
        selected.addEventListener('click', function(e) {
            const selectContainer = this.closest('.custom-select');
            const itemsContainer = selectContainer.querySelector('.select-items');
            
            // Закрыть другие открытые списки
            document.querySelectorAll('.select-items').forEach(container => {
                if (container !== itemsContainer) {
                    container.classList.remove('select-open');
                    container.closest('.custom-select').querySelector('.select-selected').classList.remove('select-arrow-active');
                }
            });

            // Переключить текущий dropdown
            itemsContainer.classList.toggle('select-open');
            this.classList.toggle('select-arrow-active');
            
            e.stopPropagation();
        });
    });

    // Обработчик для элементов dropdown (select-item)
    document.querySelectorAll('.select-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const selectContainer = this.closest('.custom-select');
            const itemsContainer = selectContainer.querySelector('.select-items');
            const selected = selectContainer.querySelector('.select-selected');
            
            // Определяем тип валюты (1 или 2) по ID контейнера
            const currencyType = selectContainer.id.includes('1') ? '1' : '2';
            
            // Закрыть dropdown после выбора
            itemsContainer.classList.remove('select-open');
            selected.classList.remove('select-arrow-active');
            
            // Обновить выбранное значение
            const currencyCode = this.getAttribute('data-currency');
            const flagUrl = this.getAttribute('data-flag');
            
            // Обновить состояние
            if (currencyType === '1') {
                state.currency1 = currencyCode;
            } else {
                state.currency2 = currencyCode;
            }
            
            // Обновить отображение
            const flagElement = selected.querySelector('.currency-flag');
            const codeElement = selected.querySelector('.currency-code-select');
            
            if (flagElement) {
                flagElement.style.backgroundImage = `url('/flags/${flagUrl}')`;
            }
            if (codeElement) {
                codeElement.textContent = currencyCode;
            }
            
            // Синхронизировать с оригинальными select
            syncWithOriginalSelects();
            
            // Обновить выделение
            markSelectedOptions();
            
            e.stopPropagation();
        });
    });

    // Блокировка закрытия при клике внутри списка
    document.querySelectorAll('.select-items').forEach(container => {
        container.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

    // Закрытие при клике вне dropdown
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select')) {
            document.querySelectorAll('.select-items').forEach(container => {
                container.classList.remove('select-open');
            });
            document.querySelectorAll('.select-selected').forEach(selected => {
                selected.classList.remove('select-arrow-active');
            });
        }
    });

    // Закрытие при нажатии Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.select-items').forEach(container => {
                container.classList.remove('select-open');
            });
            document.querySelectorAll('.select-selected').forEach(selected => {
                selected.classList.remove('select-arrow-active');
            });
        }
    });
}

// Синхронизация с оригинальными select элементами
function syncWithOriginalSelects() {
    const numeratorSelect = document.getElementById('numerator');
    const denominatorSelect = document.getElementById('denumerator');
    
    if (numeratorSelect) numeratorSelect.value = state.currency1;
    if (denominatorSelect) denominatorSelect.value = state.currency2;
}

// Помечаем выбранные опции
function markSelectedOptions() {
    markSelectedOption('1', state.currency1);
    markSelectedOption('2', state.currency2);
}

function markSelectedOption(currencyType, currency) {
    const dropdown = document.getElementById(`currency${currencyType}-dropdown`);
    if (dropdown) {
        dropdown.querySelectorAll('.select-item').forEach(item => {
            item.classList.remove('selected');
            if (item.getAttribute('data-currency') === currency) {
                item.classList.add('selected');
            }
        });
    }
}

// Обработчики для навигации (если используются jQuery)
if (typeof jQuery !== 'undefined') {
    jQuery(".main-page-link").on("click", function(e) {
        e.preventDefault();
        const route = jQuery(this).data('route');
        window.location.href = route;
    });

    jQuery(".course-graphics-link").on("click", function(e) {
        e.preventDefault();
        const route = jQuery(this).data('route');
        window.location.href = route;
    });
}

// Инициализация при изменении оригинальных select (на случай ручного изменения)
document.addEventListener('DOMContentLoaded', function() {
    const numeratorSelect = document.getElementById('numerator');
    const denominatorSelect = document.getElementById('denumerator');
    
    if (numeratorSelect) {
        numeratorSelect.addEventListener('change', function() {
            state.currency1 = this.value;
            updateCurrencyDisplay('1', state.currency1);
            markSelectedOptions();
        });
    }
    
    if (denominatorSelect) {
        denominatorSelect.addEventListener('change', function() {
            state.currency2 = this.value;
            updateCurrencyDisplay('2', state.currency2);
            markSelectedOptions();
        });
    }
});

// Обновление отображения валюты
function updateCurrencyDisplay(currencyType, currency, flag = null) {
    const prefix = `currency${currencyType}`;
    const flagElement = document.getElementById(`${prefix}-flag`);
    const codeElement = document.getElementById(`${prefix}-code-select`);
    
    if (!flagElement || !codeElement) return;
    
    if (!flag) {
        flag = `${currency.toLowerCase()}.svg`;
    }
    
    flagElement.style.backgroundImage = `url('/flags/${flag}')`;
    codeElement.textContent = currency;
}