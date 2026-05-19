/* ── SettingsPage – settings.js ────────────────────────────────────
   Converted from SettingsPage.tsx (React → vanilla JS)
   ─────────────────────────────────────────────────────────────── */

(function () {
  'use strict';

  /* ── State ─────────────────────────────────────────────────────── */
  const state = {
    profile: {
      name:    'Sarah Thompson',
      email:   'sarah.thompson@email.com',
      phone:   '+1 (555) 123-4567',
      address: '123 Main Street, Cityville, CA 90210',
    },
    notifications: {
      email: true, sms: true, push: true,
      attendance: true, assignments: true, messages: true, newsletter: false,
    },
    preferences: { language: 'en', timezone: 'PST', theme: 'light' },
    showPassword: false,
  };

  /* ── Tab switching ─────────────────────────────────────────────── */
  const tabBtns   = document.querySelectorAll('.tab-btn');
  const tabPanels = document.querySelectorAll('.tab-panel');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tab;

      tabBtns.forEach(b => {
        b.classList.toggle('active', b.dataset.tab === target);
        b.setAttribute('aria-selected', b.dataset.tab === target ? 'true' : 'false');
      });

      tabPanels.forEach(panel => {
        panel.classList.toggle('active', panel.id === 'tab-' + target);
      });
    });
  });

  /* ── Password visibility toggle ────────────────────────────────── */
  document.querySelectorAll('.toggle-pw-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      state.showPassword = !state.showPassword;
      const target = document.getElementById(this.dataset.target);
      if (target) target.type = state.showPassword ? 'text' : 'password';

      // Swap eye / eye-off icons on this button
      this.querySelector('.eye-icon').classList.toggle('hidden', state.showPassword);
      this.querySelector('.eye-off-icon').classList.toggle('hidden', !state.showPassword);

      // Also toggle all other password inputs in the security tab
      document.querySelectorAll('#tab-security .field-input[type="password"], #tab-security .field-input[type="text"]').forEach(inp => {
        if (inp.id !== this.dataset.target) {
          inp.type = state.showPassword ? 'text' : 'password';
        }
      });
    });
  });

  /* ── Save handlers ─────────────────────────────────────────────── */

  // Profile
  document.getElementById('saveProfile').addEventListener('click', () => {
    state.profile.name    = document.getElementById('name').value;
    state.profile.email   = document.getElementById('email').value;
    state.profile.phone   = document.getElementById('phone').value;
    state.profile.address = document.getElementById('address').value;
    showToast('Profile updated successfully!');
  });

  // Notifications
  document.getElementById('saveNotifications').addEventListener('click', () => {
    document.querySelectorAll('[data-notif]').forEach(cb => {
      state.notifications[cb.dataset.notif] = cb.checked;
    });
    showToast('Notification preferences saved!');
  });

  // Password update
  document.getElementById('updatePassword').addEventListener('click', () => {
    const current  = document.getElementById('currentPassword').value;
    const next     = document.getElementById('newPassword').value;
    const confirm  = document.getElementById('confirmPassword').value;

    if (!current || !next || !confirm) {
      showToast('Please fill in all password fields.');
      return;
    }
    if (next !== confirm) {
      showToast('New passwords do not match.');
      return;
    }
    // Clear fields after success
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value     = '';
    document.getElementById('confirmPassword').value = '';
    showToast('Password updated successfully!');
  });

  // Preferences
  document.getElementById('savePreferences').addEventListener('click', () => {
    state.preferences.language = document.getElementById('language').value;
    state.preferences.timezone = document.getElementById('timezone').value;
    state.preferences.theme    = document.getElementById('theme').value;
    showToast('Preferences updated successfully!');
  });

  /* ── Toast ─────────────────────────────────────────────────────── */
  const toast    = document.getElementById('toast');
  const toastMsg = document.getElementById('toastMsg');
  let toastTimer = null;

  function showToast(message) {
    toastMsg.textContent = message;
    toast.classList.remove('hidden');
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.add('hidden'), 3500);
  }

  /* ── Entrance animations ────────────────────────────────────────── */
  const animatables = document.querySelectorAll('.animate-in');

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          setTimeout(() => entry.target.classList.add('visible'), i * 80);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    animatables.forEach(el => observer.observe(el));
  } else {
    animatables.forEach(el => el.classList.add('visible'));
  }

})();
