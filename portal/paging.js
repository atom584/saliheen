(() => {
  const regionSelector = '[data-pager-region]';
  if (!document.querySelector(regionSelector) || !window.fetch || !window.DOMParser) return;

  const bind = () => {
    document.querySelectorAll(regionSelector + ' .pager a[href]').forEach((link) => {
      if (link.dataset.pagerBound) return;
      link.dataset.pagerBound = '1';
      link.addEventListener('click', (event) => {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        event.preventDefault();
        load(link.href, true);
      });
    });
  };

  const load = async (url, push) => {
    try {
      const response = await fetch(url, {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });
      if (!response.ok) {
        window.location.assign(url);
        return;
      }
      const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
      let replaced = false;
      document.querySelectorAll(regionSelector).forEach((region) => {
        const next = doc.getElementById(region.id);
        if (!next) return;
        region.replaceWith(next);
        replaced = true;
      });
      if (!replaced) {
        window.location.assign(url);
        return;
      }
      if (push) history.pushState({ pager: true }, '', url);
      bind();
      reveal(url);
    } catch (error) {
      window.location.assign(url);
    }
  };

  const reveal = (url) => {
    const target = document.querySelector(regionSelector);
    if (!target) return;
    if (target.getBoundingClientRect().top < 0) {
      target.scrollIntoView({ block: 'start', behavior: 'smooth' });
    }
    const hash = new URL(url, window.location.href).hash;
    if (hash) {
      const anchor = document.querySelector(hash);
      if (anchor) anchor.scrollIntoView({ block: 'start', behavior: 'smooth' });
    }
  };

  bind();
  window.addEventListener('popstate', () => load(window.location.href, false));
})();
