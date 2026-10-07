document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.money-input').forEach(input => {
    const format = value => {
      const digits = String(value || '').replace(/\D/g, '');
      return digits ? new Intl.NumberFormat('id-ID').format(Number(digits)) : '';
    };
    input.value = format(input.value);
    input.addEventListener('input', () => { input.value = format(input.value); });
    input.form?.addEventListener('submit', () => {
      input.value = input.value.replace(/\D/g, '');
    });
  });
});
