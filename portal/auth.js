(() => {
  document.querySelectorAll('.reveal').forEach((btn) => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      if (!input) return;
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      btn.setAttribute('aria-pressed', String(show));
    });
  });

  document.querySelectorAll('form').forEach((form) => {
    if (form.matches('[data-demo-login]')) return;
    const password = form.querySelector('[data-password]');
    const confirm = form.querySelector('[data-confirm]');

    if (password && confirm) {
      confirm.addEventListener('input', () => confirm.setCustomValidity(''));
    }

    form.addEventListener('submit', (event) => {
      event.preventDefault();

      if (password && confirm && password.value !== confirm.value) {
        confirm.setCustomValidity('Passwords do not match.');
        confirm.reportValidity();
        return;
      }

      const status = form.querySelector('.form-status');
      if (status) {
        status.classList.add('show');
        status.focus({ preventScroll: true });
      }
    });
  });
})();
