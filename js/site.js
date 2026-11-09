const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');

if (menuToggle && navLinks) {
  menuToggle.addEventListener('click', () => {
    const open = navLinks.classList.toggle('open');
    menuToggle.setAttribute('aria-expanded', String(open));
    menuToggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    menuToggle.textContent = open ? '✕' : '☰';
  });

  navLinks.querySelectorAll('a').forEach((link) =>
    link.addEventListener('click', () => {
      navLinks.classList.remove('open');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Open navigation');
      menuToggle.textContent = '☰';
    })
  );

  const segments = window.location.pathname.split('/').filter(Boolean);
  const last = (segments[segments.length - 1] || '').replace(/\.(html|php)$/, '');
  const section = last ? '/' + last : '/';
  navLinks.querySelectorAll('a[data-page]').forEach((link) => {
    link.classList.toggle('active', link.dataset.page === section);
  });
}

const year = document.getElementById('year');
if (year) year.textContent = new Date().getFullYear();

const whatsappButton = document.createElement('a');
whatsappButton.className = 'whatsapp-button';
whatsappButton.href = 'https://wa.me/8801713079046';
whatsappButton.target = '_blank';
whatsappButton.rel = 'noopener noreferrer';
whatsappButton.setAttribute('aria-label', 'Chat with Saliheen & Associates on WhatsApp');
whatsappButton.innerHTML = '<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M20.5 3.5A11.8 11.8 0 0 0 12.1 0C5.5 0 .1 5.4.1 12c0 2.1.6 4.2 1.6 6L0 24l6.2-1.6a12 12 0 0 0 5.9 1.5h.1c6.6 0 12-5.4 12-12a11.9 11.9 0 0 0-3.7-8.4ZM12.1 22a10 10 0 0 1-5.1-1.4l-.4-.2-3.7 1 1-3.6-.3-.4A9.9 9.9 0 1 1 12.1 22Zm5.5-7.4c-.3-.2-1.7-.9-2-.9s-.5-.2-.7.2-.8.9-1 1.1-.4.2-.7 0a8.2 8.2 0 0 1-2.4-1.5 9 9 0 0 1-1.6-2c-.2-.3 0-.5.1-.6l.5-.6c.2-.2.2-.4.3-.6s0-.4 0-.6L9.2 7c-.2-.5-.5-.4-.7-.4h-.6c-.2 0-.6.1-.9.4s-1.2 1.2-1.2 2.8 1.2 3.2 1.4 3.4 2.4 3.7 5.8 5c.8.3 1.4.5 1.8.6.8.2 1.5.2 2 .1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4s-.2-.3-.5-.5Z"/></svg><span>WhatsApp</span>';
document.body.append(whatsappButton);

const backTop = document.getElementById('backTop');
if (backTop) {
  window.addEventListener(
    'scroll',
    () => backTop.classList.toggle('show', window.scrollY > 450),
    { passive: true }
  );
  backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

const contactForm = document.getElementById('contactForm');
if (contactForm) {
  contactForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const subject = encodeURIComponent('Website enquiry — ' + data.get('topic'));
    const body = encodeURIComponent(
      'Name: ' + data.get('name') +
      '\nEmail: ' + data.get('email') +
      '\nArea: ' + data.get('topic') +
      '\n\nMessage:\n' + data.get('message')
    );
    document.getElementById('formStatus').textContent =
      'Your email draft will open. Review the details before sending.';
    window.location.href = 'mailto:info@nsaliheen.com,nazmus.nazlee@gmail.com?subject=' + subject + '&body=' + body;
  });
}
