(() => {
  const KEY = 'sa_demo_session';

  const USERS = {
    admin: { email: 'admin@nsaliheen.com', password: 'Admin@2026', name: 'Nazmus Saliheen' },
    client: { email: 'client@deltatextiles.com', password: 'Client@2026', name: 'Ayesha Rahman' }
  };

  const readSession = () => {
    try {
      return JSON.parse(sessionStorage.getItem(KEY) || localStorage.getItem(KEY) || 'null');
    } catch (error) {
      return null;
    }
  };

  const clearSession = () => {
    sessionStorage.removeItem(KEY);
    localStorage.removeItem(KEY);
  };

  const body = document.body;
  const guardRole = body.dataset.demoRole;

  if (guardRole) {
    const session = readSession();
    if (!session || session.role !== guardRole) {
      window.location.replace(body.dataset.demoLogin || '../');
      return;
    }

    document.querySelectorAll('[data-demo-user]').forEach((el) => {
      el.textContent = session.name || 'Demo user';
    });

    document.querySelectorAll('a[data-demo-logout], button[data-demo-logout]').forEach((link) => {
      link.addEventListener('click', (event) => {
        event.preventDefault();
        clearSession();
        window.location.href = body.dataset.demoLogout || '../';
      });
    });
  }

  document.querySelectorAll('form[data-demo-login]').forEach((form) => {
    const user = USERS[form.dataset.demoLogin];
    const status = form.querySelector('.form-status');
    if (!user) return;

    form.querySelectorAll('input').forEach((input) => {
      input.addEventListener('input', () => {
        input.setAttribute('aria-invalid', 'false');
        if (status) status.classList.remove('show', 'error');
      });
    });

    form.addEventListener('submit', (event) => {
      event.preventDefault();

      const email = form.querySelector('input[name="email"]').value.trim().toLowerCase();
      const password = form.querySelector('input[name="password"]').value;
      const remember = form.querySelector('input[name="remember"]');

      if (email !== user.email || password !== user.password) {
        if (status) {
          status.textContent = 'Those demo credentials do not match. Use the demo details shown below.';
          status.classList.add('show', 'error');
          status.focus({ preventScroll: true });
        }
        return;
      }

      clearSession();
      const session = { role: form.dataset.demoLogin, email: user.email, name: user.name, at: Date.now() };
      const store = remember && remember.checked ? localStorage : sessionStorage;
      store.setItem(KEY, JSON.stringify(session));
      window.location.href = form.dataset.demoRedirect;
    });
  });

  document.querySelectorAll('[data-demo-fill]').forEach((button) => {
    button.addEventListener('click', () => {
      const role = button.dataset.demoFill;
      const form = document.querySelector(`form[data-demo-login="${role}"]`);
      const user = USERS[role];
      if (!form || !user) return;
      form.querySelector('input[name="email"]').value = user.email;
      form.querySelector('input[name="password"]').value = user.password;
      form.querySelector('input[name="email"]').focus();
      const status = form.querySelector('.form-status');
      if (status) status.classList.remove('show', 'error');
    });
  });
})();
