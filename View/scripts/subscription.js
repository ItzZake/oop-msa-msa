/* ── SubscriptionPage – subscription.js ────────────────────────────
   On plan selection: saves plan + billing data to sessionStorage,
   then redirects to index.html (payment page).
   ─────────────────────────────────────────────────────────────── */

(function () {
  'use strict';

  /* ── Plan definitions (prices must match HTML data-monthly/yearly) ── */
  const PLAN_DATA = {
    basic: {
      name: 'Basic',
      monthly: [
        { label: 'Monthly Tuition',    amount:  850.00 },
        { label: 'Registration Fee',   amount:  100.00 },
        { label: 'Activity Materials', amount:   30.00 },
        { label: 'Early Bird Discount',amount:  -50.00 }
      ],
      yearly: [
        { label: 'Monthly Tuition',    amount:  708.00 },
        { label: 'Registration Fee',   amount:  100.00 },
        { label: 'Activity Materials', amount:   30.00 },
        { label: 'Early Bird Discount',amount:  -88.00 }
      ]
    },
    premium: {
      name: 'Premium',
      monthly: [
        { label: 'Monthly Tuition',    amount: 1200.00 },
        { label: 'Registration Fee',   amount:  150.00 },
        { label: 'Activity Materials', amount:   50.00 },
        { label: 'Early Bird Discount',amount: -100.00 }
      ],
      yearly: [
        { label: 'Monthly Tuition',    amount: 1000.00 },
        { label: 'Registration Fee',   amount:  150.00 },
        { label: 'Activity Materials', amount:   50.00 },
        { label: 'Early Bird Discount',amount: -100.00 }
      ]
    },
    elite: {
      name: 'Elite',
      monthly: [
        { label: 'Monthly Tuition',    amount: 1650.00 },
        { label: 'Registration Fee',   amount:  200.00 },
        { label: 'Activity Materials', amount:   75.00 },
        { label: 'Early Bird Discount',amount: -125.00 }
      ],
      yearly: [
        { label: 'Monthly Tuition',    amount: 1375.00 },
        { label: 'Registration Fee',   amount:  200.00 },
        { label: 'Activity Materials', amount:   75.00 },
        { label: 'Early Bird Discount',amount: -150.00 }
      ]
    }
  };

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
    labelMonthly.dataset.active = String(!isYearly);
    labelYearly.dataset.active  = String(isYearly);
    saveBadge.classList.toggle('hidden', !isYearly);

    priceEls.forEach(el => {
      const val = isYearly ? parseInt(el.dataset.yearly, 10) : parseInt(el.dataset.monthly, 10);
      el.textContent = '$' + val.toLocaleString();
    });

    billedEls.forEach(el => el.classList.toggle('hidden', !isYearly));
  }

  billingToggle.addEventListener('change', function () {
    isYearly = this.checked;
    updatePricing();
  });

  /* ── Plan buttons → save to sessionStorage + redirect ─────────── */
  document.querySelectorAll('.plan-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const card    = this.closest('.plan-card');
      const planKey = card ? card.dataset.plan : null;

      if (!planKey || !PLAN_DATA[planKey]) {
        showToast('Unknown plan selected.');
        return;
      }

      const cycle     = isYearly ? 'yearly' : 'monthly';
      // billingCycle DB ENUM is 'Monthly' or 'Termly'
      const dbCycle   = isYearly ? 'Termly' : 'Monthly';
      const lineItems = PLAN_DATA[planKey][cycle];
      const planName  = PLAN_DATA[planKey].name;

      // Save everything the payment page needs
      sessionStorage.setItem('selectedPlan', JSON.stringify({
        planKey,
        planName,
        billingCycle: dbCycle,
        isYearly,
        lineItems
      }));

      // Redirect to payment page
      window.location.href = 'index.html';
    });
  });

  /* ── CTA button ─────────────────────────────────────────────────── */
  document.querySelector('.cta-btn').addEventListener('click', () => {
    showToast("Consultation request sent! We'll be in touch soon.");
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
