(() => {
  const config = document.getElementById('exploreConfig');
  const select = document.getElementById('serviceSelect');
  const detail = document.getElementById('serviceDetail');
  const status = document.getElementById('exploreStatus');
  const dataNode = document.getElementById('serviceData');
  if (!config || !select || !detail || !dataNode) return;

  let services = [];
  try {
    services = JSON.parse(dataNode.textContent || '[]');
  } catch (error) {
    services = [];
  }

  const mode = config.dataset.mode || 'guest';
  const api = config.dataset.api;
  const dashboardUrl = config.dataset.dashboard;
  const csrf = config.dataset.csrf;
  const icons = ['i-scale', 'i-check', 'i-shield', 'i-lock', 'i-briefcase', 'i-bank', 'i-gavel', 'i-chart'];

  const escapeHtml = (value) =>
    String(value).replace(/[&<>"']/g, (char) => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
    }[char]));

  const findService = (id) => services.find((service) => String(service.id) === String(id)) || null;

  const setStatus = (message, tone = 'error') => {
    if (!status) return;
    status.textContent = message;
    status.classList.add('show');
    status.classList.toggle('error', tone === 'error');
    status.classList.toggle('ok', tone === 'ok');
    status.focus({ preventScroll: true });
  };

  const clearStatus = () => {
    if (!status) return;
    status.textContent = '';
    status.classList.remove('show', 'error', 'ok');
  };

  const iconFor = (icon) => `<svg viewBox="0 0 24 24"><use href="#${icons.includes(icon) ? icon : 'i-scale'}"/></svg>`;

  const actionLabel = () => (mode === 'client' ? 'Request This Service' : 'Continue to Registration');

  const accountLink = () => {
    if (mode !== 'guest') return '';
    return `<a href="#" class="change-link" data-intent="login">Already have an account? Sign in</a>`;
  };

  const renderEmpty = () => {
    detail.innerHTML = `
      <div class="detail-empty">
        <div>
          <div class="empty-mark"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg></div>
          <h3>Your service details will appear here</h3>
          <p>Select a legal service from the list to see its description, included features and the next step.</p>
        </div>
      </div>`;
  };

  const renderDetail = (service) => {
    if (!service) {
      renderEmpty();
      return;
    }
    const features = (service.features || [])
      .map((feature) => `<li>${escapeHtml(feature)}</li>`)
      .join('');
    detail.innerHTML = `
      <div class="service-detail-head">
        <div class="service-detail-icon">${iconFor(service.icon)}</div>
        <div>
          <p class="kicker">Selected service</p>
          <h2>${escapeHtml(service.name)}</h2>
        </div>
      </div>
      <p class="description">${escapeHtml(service.description)}</p>
      <div class="detail-divider"></div>
      <p class="detail-features-title">Included service features</p>
      <ul class="detail-features">${features}</ul>
      <div class="detail-actions">
        <button class="btn-service" type="button" data-intent="${mode === 'admin' ? 'admin' : mode === 'client' ? 'request' : 'register'}" ${mode === 'admin' ? 'disabled' : ''}>
          ${escapeHtml(actionLabel())}
          <svg viewBox="0 0 24 24"><use href="#i-arrow"/></svg>
        </button>
        <a href="#" class="change-link" data-change>Change service</a>
        ${accountLink()}
      </div>`;
  };

  const selectService = (id) => {
    const service = findService(id);
    clearStatus();
    renderDetail(service);
  };

  select.addEventListener('change', () => selectService(select.value));

  detail.addEventListener('click', async (event) => {
    const change = event.target.closest('[data-change]');
    if (change) {
      event.preventDefault();
      select.value = '';
      renderEmpty();
      clearStatus();
      select.focus();
      return;
    }

    const trigger = event.target.closest('[data-intent]');
    if (!trigger) return;
    event.preventDefault();

    const service = findService(select.value);
    if (!service) {
      setStatus('Select a legal service before continuing.');
      select.focus();
      return;
    }

    const intent = trigger.dataset.intent;
    if (intent === 'admin') return;

    trigger.disabled = true;
    const original = trigger.innerHTML;
    trigger.textContent = 'Please wait…';
    clearStatus();

    try {
      const response = await fetch(api, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-Token': csrf,
        },
        body: JSON.stringify({ service_id: service.id, intent }),
      });
      const result = await response.json().catch(() => ({ ok: false, error: 'Unexpected server response.' }));

      if (!response.ok || !result.ok) {
        setStatus(result.error || 'The service could not be selected. Please try again.');
        trigger.disabled = false;
        trigger.innerHTML = original;
        return;
      }

      if (result.redirect) {
        window.location.assign(result.redirect);
        return;
      }

      if (result.mode === 'requested') {
        setStatus(result.message || 'Your service request has been submitted for review.', 'ok');
        trigger.disabled = true;
        trigger.innerHTML = 'Request submitted';
        const link = document.createElement('a');
        link.href = dashboardUrl;
        link.className = 'btn-service';
        link.style.marginTop = '10px';
        link.textContent = 'Go to My Dashboard';
        trigger.insertAdjacentElement('afterend', link);
      }
    } catch (error) {
      setStatus('A network error occurred. Check your connection and try again.');
      trigger.disabled = false;
      trigger.innerHTML = original;
    }
  });

  const initial = detail.dataset.active ? findService(detail.dataset.active) : findService(select.value);
  if (select.value) {
    selectService(select.value);
  } else if (initial) {
    select.value = initial.id;
    selectService(initial.id);
  }
})();
