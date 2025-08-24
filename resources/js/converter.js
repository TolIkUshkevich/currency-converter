document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const fromAmount = document.getElementById('fromAmount');
    const toAmount = document.getElementById('toAmount');
    const fromCurrency = document.getElementById('fromCurrency');
    const toCurrency = document.getElementById('toCurrency');
    const swapBtn = document.getElementById('swapBtn');
  
    // Exchange rates (from your prepared data)
    const rates = {
      USD: 1.0,
      EUR: 0.93,
      GBP: 0.76
    };
  
    // Calculate function
    function calculate() {
      const rate = rates[toCurrency.value] / rates[fromCurrency.value];
      toAmount.value = (fromAmount.value * rate).toFixed(2);
    }
  
    // Event listeners
    fromAmount.addEventListener('input', calculate);
    fromCurrency.addEventListener('change', calculate);
    toCurrency.addEventListener('change', calculate);
  
    // Swap currencies
    swapBtn.addEventListener('click', () => {
      [fromCurrency.value, toCurrency.value] = [toCurrency.value, fromCurrency.value];
      calculate();
    });
  
    // Initial calculation
    calculate();
  });