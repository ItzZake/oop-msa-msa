/* ── SubscriptionPage – subscription.js ────────────────────────────
   Converted from SubscriptionPage.tsx (React → vanilla JS)
   ─────────────────────────────────────────────────────────────── */

(function () {
  'use strict';

  /* ── State ─────────────────────────────────────────────────────── */
  let isYearly = false;

  /* ── Billing toggle ────────────────────────────────────────────── */
  const billingToggle  = document.getElementById('billingToggle');
  const labelMonthly   = document.getElementById('labelMonthly');
  const labelYearly    = document.getElementById('labelYearly');
  const saveBadge      = document.getElementById('saveBadge');
  const priceEls       = document.querySelectorAll('.plan-price');
  const billedEls      = document.querySelectorAll('.plan-billed');

  function updatePricing() {
    // Labels
    labelMonthly.dataset.active = String(!isYearly);
    labelYearly.dataset.active  = String(isYearly);

    // Save badge
    saveBadge.classList.toggle('hidden', !isYearly);

    // Prices
    priceEls.forEach(el => {
      const monthly = parseInt(el.dataset.monthly, 10);
      const yearly  = parseInt(el.dataset.yearly, 10);
      const val = isYearly ? yearly : monthly;
      el.textContent = '$' + val.toLocaleString();
    });

    // "Billed annually" lines
    billedEls.forEach(el => {
      el.classList.toggle('hidden', !isYearly);
    });
  }

  billingToggle.addEventListener('change', function () {
    isYearly = this.checked;
    updatePricing();
  });

  /* ── Plan buttons ──────────────────────────────────────────────── */
  document.querySelectorAll('.plan-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const card     = this.closest('.plan-card');
      const planName = card ? card.dataset.plan : 'plan';
      const label    = planName.charAt(0).toUpperCase() + planName.slice(1);
      const billing  = isYearly ? 'yearly' : 'monthly';
      showToast(`${label} plan selected (${billing})!`);
    });
  });

  /* ── CTA button ─────────────────────────────────────────────────── */
  document.querySelector('.cta-btn').addEventListener('click', () => {
    showToast('Consultation request sent! We\'ll be in touch soon.');
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

  /* ── Entrance animations (IntersectionObserver) ────────────────── */
  const animatables = document.querySelectorAll('.animate-in');

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, i) => {
          if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), i * 100);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.08 }
    );
    animatables.forEach(el => observer.observe(el));
  } else {
    animatables.forEach(el => el.classList.add('visible'));
  }

})();
