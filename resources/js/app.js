import '../css/app.css';

const state = {
    currency1: 'USD',
    currency2: 'EUR'
};

const config = {
    currencies: window.currencyConfig || {}
};


document.addEventListener('DOMContentLoaded', function() {
    initDropdowns();
    syncWithOriginalSelects();
    markSelectedOptions();
    setupEventListeners();
    
    if (document.getElementById('amount1')) {
        convertCurrency();
    }
});

function initDropdowns() {
    document.querySelectorAll('.select-selected').forEach(selected => {
        selected.addEventListener('click', function(e) {
            const selectContainer = this.closest('.custom-select');
            const itemsContainer = selectContainer.querySelector('.select-items');
            
            document.querySelectorAll('.select-items').forEach(container => {
                if (container !== itemsContainer) {
                    container.classList.remove('select-open');
                    container.closest('.custom-select').querySelector('.select-selected').classList.remove('select-arrow-active');
                }
            });

            itemsContainer.classList.toggle('select-open');
            this.classList.toggle('select-arrow-active');
            
            e.stopPropagation();
        });
    });

    document.querySelectorAll('.select-item').forEach(item => {
        item.addEventListener('click', function(e) {
            const selectContainer = this.closest('.custom-select');
            const itemsContainer = selectContainer.querySelector('.select-items');
            const selected = selectContainer.querySelector('.select-selected');
            
            const currencyType = selectContainer.id.includes('1') ? '1' : '2';
            
            itemsContainer.classList.remove('select-open');
            selected.classList.remove('select-arrow-active');
            
            const currencyCode = this.getAttribute('data-currency');
            
            if (currencyType === '1') {
                state.currency1 = currencyCode;
            } else {
                state.currency2 = currencyCode;
            }
            
            const codeElement = selected.querySelector('.currency-code, .currency-code-select');
            
            if (codeElement) {
                codeElement.textContent = currencyCode;
            }
            
            syncWithOriginalSelects();
            
            markSelectedOptions();
            
            if (document.getElementById('amount1')) {
                convertCurrency();
            }
            
            e.stopPropagation();
        });
    });

    document.querySelectorAll('.select-items').forEach(container => {
        container.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

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

function syncWithOriginalSelects() {
    const fromCurrencySelect = document.getElementById('from_currency');
    const toCurrencySelect = document.getElementById('to_currency');
    
    const numeratorSelect = document.getElementById('numerator');
    const denominatorSelect = document.getElementById('denumerator');
    
    if (fromCurrencySelect) fromCurrencySelect.value = state.currency1;
    if (toCurrencySelect) toCurrencySelect.value = state.currency2;
    
    if (numeratorSelect) numeratorSelect.value = state.currency1;
    if (denominatorSelect) denominatorSelect.value = state.currency2;
}

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

function setupEventListeners() {
    document.querySelectorAll(".main-page-link, .course-graphics-link").forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const route = this.getAttribute('data-route');
            window.location.href = route;
        });
    });
    
    if (document.getElementById('amount1')) {
        document.getElementById('amount1').addEventListener('input', convertCurrency);
        document.getElementById('amount2').addEventListener('input', reverseConvertCurrency);
        
        document.getElementById('swapBtn').addEventListener('click', swapCurrencies);
        
        document.querySelectorAll('.frequent-currency').forEach(item => {
            item.addEventListener('click', function() {
                const currency = this.getAttribute('data-currency');
                state.currency1 = currency;
                updateCurrencyDisplay('1', currency);
                syncWithOriginalSelects();
                markSelectedOptions();
                convertCurrency();
            });
        });
    }
    
    const fromCurrencySelect = document.getElementById('from_currency');
    const toCurrencySelect = document.getElementById('to_currency');
    const numeratorSelect = document.getElementById('numerator');
    const denominatorSelect = document.getElementById('denumerator');
    
    if (fromCurrencySelect) {
        fromCurrencySelect.addEventListener('change', function() {
            state.currency1 = this.value;
            updateCurrencyDisplay('1', state.currency1);
            markSelectedOptions();
            if (document.getElementById('amount1')) convertCurrency();
        });
    }
    
    if (toCurrencySelect) {
        toCurrencySelect.addEventListener('change', function() {
            state.currency2 = this.value;
            updateCurrencyDisplay('2', state.currency2);
            markSelectedOptions();
            if (document.getElementById('amount1')) convertCurrency();
        });
    }
    
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
}

function convertCurrency() {
    const amount = parseFloat(document.getElementById('amount1').value);
    if (isNaN(amount)) return;
    
    fetch('/convert', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            fromCharCode: state.currency1,
            toCharCode: state.currency2,
            amount: amount
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('amount2').value = data.result;
            updateRateInfo(data.rate);
        }
    })
    .catch(error => {
        console.error('Ошибка конвертации:', error);
    });
}

function reverseConvertCurrency() {
    const amount = parseFloat(document.getElementById('amount2').value);
    if (isNaN(amount)) return;
    
    fetch('/convert', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            fromCharCode: state.currency2,
            toCharCode: state.currency1,
            amount: amount
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('amount1').value = data.result;
            updateRateInfo(1 / data.rate);
        }
    })
    .catch(error => {
        console.error('Ошибка конвертации:', error);
    });
}

function swapCurrencies() {
    const amount1Value = document.getElementById('amount1').value;
    
    [state.currency1, state.currency2] = [state.currency2, state.currency1];
    
    updateCurrencyDisplay('1', state.currency1);
    updateCurrencyDisplay('2', state.currency2);
    
    document.getElementById('amount1').value = amount1Value;
    
    document.getElementById('amount2').value = '';
    
    syncWithOriginalSelects();
    
    markSelectedOptions();
    
    convertCurrency();
}

function updateCurrencyDisplay(currencyType, currency) {
    const prefix = `currency${currencyType}`;
    const codeElement = document.getElementById(`${prefix}-code`) || document.getElementById(`${prefix}-code-select`);
    
    if (!codeElement) return;
    
    codeElement.textContent = currency;
}

function updateRateInfo(rate) {
    const rateInfo = document.getElementById('rate-info');
    if (rateInfo && rate && typeof rate === 'number') {
        rateInfo.textContent = `1 ${state.currency1} = ${rate.toFixed(6)} ${state.currency2}`;
    } else if (rateInfo) {
        rateInfo.textContent = 'Обновление курсов в реальном времени';
    }
}

const raw = Array.isArray(window.currencyData) ? window.currencyData : [];
const MS_PER_DAY = 24*60*60*1000;

const allData = raw.map(item => {
  const [day, month, year] = item[0].split('/').map(Number);
  const ts = Date.UTC(year, month-1, day);
  return { x: ts, y: Number(item[1]), label: `${String(day).padStart(2,'0')}/${String(month).padStart(2,'0')}/${year}` };
})

let chart;
let currentPeriod = 'week';

function formatDate(ts) {
  const d = new Date(ts);
  const day = String(d.getUTCDate()).padStart(2,'0');
  const month = String(d.getUTCMonth()+1).padStart(2,'0');
  const year = d.getUTCFullYear();
  return `${day}/${month}/${year}`;
}

function getFilteredData(period) {
  if (!allData.length) return [];
  const last = allData[allData.length-1].x;
  const daysBack = period==='week'?7:period==='month'?30:365;
  return allData.filter(d => d.x >= last - daysBack*MS_PER_DAY);
}

function getXAxisLabels(period, seriesData) {
  if (!seriesData.length) return [];

  const lastPoint = seriesData[seriesData.length-1];
  let labels = [];

  if (period === 'week') {
    labels = seriesData.map(d => d.label);
  } else if (period === 'month') {
    labels = seriesData.filter((d,i) => {
      const day = new Date(d.x).getUTCDate();
      return day === 1 || (i % 5 === 0) || d === lastPoint;
    }).map(d => d.label);
    if (!labels.includes(lastPoint.label)) labels.push(lastPoint.label);
  } else {
    labels = seriesData.filter(d => {
      const day = new Date(d.x).getUTCDate();
      return day === 1;
    }).map(d => d.label);
    if (!labels.includes(lastPoint.label)) labels.push(lastPoint.label);
  }

  return labels;
}

function getXAxisOptions(period, seriesData) {
  const labels = getXAxisLabels(period, seriesData);
  return {
    type: 'category',
    categories: seriesData.map(d => d.label),
    labels: {
      rotate: -45,
      formatter: function(val) {
        if (labels.includes(val)) return val;
        return '';
      }
    }
  };
}

function initChart(initialData) {
  chart = new ApexCharts(document.querySelector("#chart"), {
    chart: { type: 'line', height: 350, zoom: { enabled: false } },
    series: [{ name: 'Курс', data: initialData.map(d => ({ x: d.label, y: d.y })) }],
    stroke: { curve: 'smooth' },
    markers: { size: 0 },
    tooltip: {
      x: {
        formatter: function(val, opts) {
          const idx = opts.dataPointIndex;
          return initialData[idx]?.label || val;
        }
      }
    },
    xaxis: getXAxisOptions(currentPeriod, initialData)
  });
  chart.render();
}

function updateChart(period) {
  currentPeriod = period;
  const filteredData = getFilteredData(period);

  chart.updateOptions({
    xaxis: getXAxisOptions(period, filteredData),
    tooltip: {
      x: {
        formatter: function(val, opts) {
          const idx = opts.dataPointIndex;
          return filteredData[idx]?.label || val;
        }
      }
    }
  }, false, true);

  chart.updateSeries([{ data: filteredData.map(d => ({ x: d.label, y: d.y })) }], true);
}

document.addEventListener("DOMContentLoaded", () => {
  initChart(getFilteredData('week'));

  document.querySelector("#week-btn")?.addEventListener('click', () => updateChart('week'));
  document.querySelector("#month-btn")?.addEventListener('click', () => updateChart('month'));
  document.querySelector("#year-btn")?.addEventListener('click', () => updateChart('year'));
});
