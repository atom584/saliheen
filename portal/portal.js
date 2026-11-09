(() => {
  const body = document.body;
  const menuBtn = document.getElementById('menuBtn');
  const closeBtn = document.getElementById('sbClose');
  const scrim = document.getElementById('scrim');
  const sidebar = document.getElementById('sidebar');

  const openNav = () => {
    body.classList.add('nav-open');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
  };

  const closeNav = () => {
    body.classList.remove('nav-open');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
  };

  if (menuBtn) menuBtn.addEventListener('click', () => {
    body.classList.contains('nav-open') ? closeNav() : openNav();
  });
  if (closeBtn) closeBtn.addEventListener('click', closeNav);
  if (scrim) scrim.addEventListener('click', closeNav);

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && body.classList.contains('nav-open')) {
      closeNav();
      if (menuBtn) menuBtn.focus();
    }
  });

  if (sidebar) {
    sidebar.querySelectorAll('.sb-link').forEach((link) => {
      link.addEventListener('click', (event) => {
        if (link.getAttribute('href') === '#') event.preventDefault();
        sidebar.querySelectorAll('.sb-link').forEach((item) => {
          item.classList.remove('active');
          item.removeAttribute('aria-current');
        });
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
        closeNav();
      });
    });
  }
})();
