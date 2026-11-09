(() => {
  document.querySelectorAll('.reveal').forEach((button) => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.target);
      if (!input) return;
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      button.setAttribute('aria-pressed', String(show));
    });
  });

  const setStatus = (form, message, tone = 'error') => {
    const status = form.querySelector('.form-status');
    if (!status) return;
    status.textContent = message;
    status.classList.add('show');
    status.classList.remove('ok', 'error');
    status.classList.add(tone);
    if (tone === 'error') status.focus({ preventScroll: true });
  };

  const clearStatus = (form) => {
    const status = form.querySelector('.form-status');
    if (status) {
      status.textContent = '';
      status.classList.remove('show', 'ok', 'error');
    }
  };

  const clearFieldError = (form, name) => {
    const input = form.querySelector(`[name="${name}"]`);
    if (!input) return;
    input.setAttribute('aria-invalid', 'false');
    const field = input.closest('.field');
    if (field) {
      field.querySelectorAll('.field-error').forEach((node) => node.remove());
    }
  };

  const showFieldError = (form, name, message) => {
    const input = form.querySelector(`[name="${name}"]`);
    if (!input) return;
    input.setAttribute('aria-invalid', 'true');
    const field = input.closest('.field');
    if (!field) return;
    field.querySelectorAll('.field-error').forEach((node) => node.remove());
    const error = document.createElement('span');
    error.className = 'field-error';
    error.textContent = message;
    field.appendChild(error);
  };

  document.querySelectorAll('form[data-api]').forEach((form) => {
    const api = form.dataset.api;
    const csrf = form.dataset.csrf || '';
    const submit = form.querySelector('[type="submit"]');
    const originalSubmit = submit ? submit.innerHTML : '';

    form.querySelectorAll('input, select').forEach((input) => {
      input.addEventListener('input', () => {
        clearFieldError(form, input.name);
        clearStatus(form);
      });
      input.addEventListener('change', () => {
        clearFieldError(form, input.name);
        clearStatus(form);
      });
    });

    const password = form.querySelector('[data-password]');
    const confirm = form.querySelector('[data-confirm]');
    if (password && confirm) {
      confirm.addEventListener('input', () => {
        if (confirm.value === '' || confirm.value === password.value) {
          confirm.setCustomValidity('');
          clearFieldError(form, confirm.name);
        }
      });
    }

    const submitForm = async (event) => {
      if (event) event.preventDefault();
      if (form.dataset.busy === '1') return;

      if (password && confirm && password.value !== confirm.value) {
        confirm.setCustomValidity('Passwords do not match.');
        confirm.reportValidity();
        return;
      }
      if (password) password.setCustomValidity('');
      if (confirm) confirm.setCustomValidity('');

      if (!form.reportValidity()) return;

      const payload = {};
      new FormData(form).forEach((value, key) => {
        payload[key] = typeof value === 'string' ? value : value.name;
      });
      payload.terms = form.querySelector('[name="terms"]') ? !!form.querySelector('[name="terms"]').checked : undefined;

      form.dataset.busy = '1';
      clearStatus(form);
      if (submit) {
        submit.disabled = true;
        submit.dataset.label = originalSubmit;
        submit.innerHTML = 'Please wait…';
      }

      try {
        const response = await fetch(api, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-Token': csrf,
          },
          body: JSON.stringify(payload),
        });
        const result = await response.json().catch(() => ({ ok: false, error: 'Unexpected server response.' }));

        if (!response.ok || !result.ok) {
          if (result.errors) {
            Object.entries(result.errors).forEach(([name, message]) => showFieldError(form, name, message));
            const first = form.querySelector('[aria-invalid="true"]');
            if (first) first.focus({ preventScroll: false });
          }
          setStatus(form, result.error || 'Please review the form and try again.', 'error');
          if (result.code === 'oauth_expired') {
            setTimeout(() => window.location.assign(document.querySelector('[data-register-url]')?.dataset.registerUrl || '/'), 2200);
          }
        } else {
          const status = form.querySelector('.form-status');
          if (status) {
            status.textContent = result.message || 'Done. Redirecting…';
            status.classList.add('show', 'ok');
            status.classList.remove('error');
          }
          if (result.redirect) {
            setTimeout(() => window.location.assign(result.redirect), 450);
          }
        }
      } catch (error) {
        setStatus(form, 'A network error occurred. Check your connection and try again.', 'error');
      } finally {
        form.dataset.busy = '0';
        if (submit) {
          submit.disabled = false;
          submit.innerHTML = originalSubmit;
        }
      }
    };

    form.addEventListener('submit', submitForm);

    if (form.dataset.autoverify === '1') {
      setTimeout(() => submitForm(), 250);
    }
  });

  document.querySelectorAll('[data-logout]').forEach((link) => {
    link.addEventListener('click', async (event) => {
      event.preventDefault();
      const csrf = link.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '';
      try {
        await fetch(link.dataset.logout, {
          method: 'POST',
          headers: { Accept: 'application/json', 'X-CSRF-Token': csrf },
        });
      } catch (error) {
        /* The redirect below still clears the local page state. */
      }
      window.location.assign(link.dataset.login || '/login');
    });
  });
})();
