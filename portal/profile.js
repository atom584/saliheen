(() => {
  'use strict';

  const menu = document.querySelector('[data-avatar-menu]');
  if (!menu) return;

  const api = menu.dataset.avatarApi || '/api/account/avatar.php';
  const trigger = menu.querySelector('[data-avatar-trigger]');
  const pop = menu.querySelector('[data-avatar-pop]');
  const uploadBtn = menu.querySelector('[data-avatar-upload]');
  const removeBtn = menu.querySelector('[data-avatar-remove]');
  const input = menu.querySelector('[data-avatar-input]');
  const status = menu.querySelector('[data-avatar-status]');
  const removeLabel = menu.querySelector('[data-avatar-remove-label]');
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  const MAX_BYTES = 2 * 1024 * 1024;
  const ALLOWED = ['image/jpeg', 'image/png', 'image/webp'];

  const setStatus = (message, tone) => {
    if (!status) return;
    status.textContent = message || '';
    status.classList.toggle('ok', tone === 'ok');
    status.classList.toggle('error', tone === 'error');
  };

  const setBusy = (busy) => {
    [uploadBtn, removeBtn].forEach((btn) => {
      if (btn) btn.disabled = busy;
    });
    if (input) input.disabled = busy;
  };

  const setAvatar = (url) => {
    menu.querySelectorAll('[data-avatar-img]').forEach((img) => {
      if (url) {
        img.src = url;
        img.hidden = false;
      } else {
        img.removeAttribute('src');
        img.hidden = true;
      }
    });
    menu.querySelectorAll('[data-avatar-initials]').forEach((el) => {
      el.hidden = !!url;
    });
    if (removeBtn) removeBtn.hidden = !url;
  };

  let removeTimer = 0;
  let confirmingRemove = false;

  const resetRemoveConfirm = () => {
    if (!confirmingRemove) return;
    confirmingRemove = false;
    window.clearTimeout(removeTimer);
    if (removeLabel) removeLabel.textContent = 'Remove';
  };

  const openMenu = (open) => {
    if (!pop || !trigger) return;
    pop.hidden = !open;
    trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (!open) {
      setStatus('', '');
      resetRemoveConfirm();
    }
  };

  trigger?.addEventListener('click', (event) => {
    event.stopPropagation();
    openMenu(pop.hidden);
  });

  document.addEventListener('click', (event) => {
    if (!pop.hidden && !menu.contains(event.target)) openMenu(false);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !pop.hidden) {
      openMenu(false);
      trigger.focus();
    }
  });

  uploadBtn?.addEventListener('click', () => {
    resetRemoveConfirm();
    setStatus('', '');
    input?.click();
  });

  input?.addEventListener('change', () => {
    const file = input.files?.[0];
    if (!file) return;
    if (!ALLOWED.includes(file.type)) {
      setStatus('Please choose a JPG, PNG or WebP image.', 'error');
      input.value = '';
      return;
    }
    if (file.size > MAX_BYTES) {
      setStatus('That image is larger than 2 MB. Please choose a smaller file.', 'error');
      input.value = '';
      return;
    }
    upload(file);
  });

  const request = async (formData) => {
    const response = await fetch(api, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'X-CSRF-Token': csrf,
      },
      body: formData,
      credentials: 'same-origin',
    });
    const data = await response.json().catch(() => ({ ok: false, error: 'Unexpected server response.' }));
    if (!response.ok || !data.ok) {
      throw new Error(data.error || 'Something went wrong. Please try again.');
    }
    return data;
  };

  const upload = async (file) => {
    const body = new FormData();
    body.append('action', 'upload');
    body.append('avatar', file, file.name);

    setBusy(true);
    setStatus('Uploading photo…');
    try {
      const result = await request(body);
      setAvatar(result.url || '');
      setStatus(result.message || 'Profile photo updated.', 'ok');
    } catch (error) {
      setStatus(error.message || 'Upload failed. Please try again.', 'error');
    } finally {
      setBusy(false);
      if (input) input.value = '';
    }
  };

  removeBtn?.addEventListener('click', async () => {
    if (!confirmingRemove) {
      confirmingRemove = true;
      if (removeLabel) removeLabel.textContent = 'Confirm remove';
      setStatus('Click Remove again to delete your photo.');
      removeTimer = window.setTimeout(resetRemoveConfirm, 8000);
      return;
    }

    resetRemoveConfirm();
    const body = new FormData();
    body.append('action', 'remove');

    setBusy(true);
    setStatus('Removing photo…');
    try {
      const result = await request(body);
      setAvatar('');
      setStatus(result.message || 'Profile photo removed.', 'ok');
    } catch (error) {
      setStatus(error.message || 'Could not remove the photo. Please try again.', 'error');
    } finally {
      setBusy(false);
    }
  });
})();
