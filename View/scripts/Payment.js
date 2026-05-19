/* ── PaymentPage – main.js ─────────────────────────────────────────
   Converted from PaymentPage.tsx (React → vanilla JS)
   ─────────────────────────────────────────────────────────────── */

(function () {
  'use strict';

  /* ── State ─────────────────────────────────────────────────────── */
  let paymentMethod = 'card';

  /* ── Element refs ──────────────────────────────────────────────── */
  const methodOptions = document.querySelectorAll('.method-option');
  const cardForm      = document.getElementById('cardForm');
  const paypalPanel   = document.getElementById('paypalPanel');
  const toast         = document.getElementById('toast');

  const cardNumberInput = document.getElementById('cardNumber');
  const expiryInput     = document.getElementById('expiry');

  /* ── Payment method selection ──────────────────────────────────── */
  function setMethod(method) {
    paymentMethod = method;

    methodOptions.forEach(opt => {
      opt.classList.toggle('active', opt.dataset.method === method);
    });

    const isCard = method === 'card' || method === 'debit';
    cardForm.classList.toggle('hidden', !isCard);
    paypalPanel.classList.toggle('hidden', isCard);
  }

  methodOptions.forEach(opt => {
    opt.addEventListener('click', () => setMethod(opt.dataset.method));
  });

  // Initialise active state
  setMethod('card');

  /* ── Input formatting ──────────────────────────────────────────── */

    /* ─────────────────────────────────────────
     3. "MORE" DROPDOWN — desktop nav
  ───────────────────────────────────────── */
const moreBtn  = document.getElementById('moreBtn');
const moreMenu = document.getElementById('moreMenu');

  if (moreBtn && moreDropdown) {
    moreBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = moreDropdown.classList.toggle('open');
      moreBtn.setAttribute('aria-expanded', isOpen);
    });

    /* Close when clicking outside */
    document.addEventListener('click', function (e) {
      if (!moreDropdown.contains(e.target)) {
        moreDropdown.classList.remove('open');
        moreBtn.setAttribute('aria-expanded', 'false');
      }
    });

    /* Close on Escape key */
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        moreDropdown.classList.remove('open');
        moreBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ══════════════════════════════════════════
     ACTIVE LINK — mark the current page
  ══════════════════════════════════════════ */
  function setActiveLinks() {
    const currentPath = window.location.pathname;

    /* All nav-link anchors (desktop + mobile) */
    document.querySelectorAll('a.nav-link[data-path]').forEach(function (link) {
      const path = link.getAttribute('data-path');
      if (path === currentPath || (path !== '/' && currentPath.startsWith(path))) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });

    /* Dropdown items */
    document.querySelectorAll('.nav-dropdown__item').forEach(function (item) {
      const href = item.getAttribute('href');
      if (href && (href === currentPath || (href !== '/' && currentPath.startsWith(href)))) {
        item.classList.add('active');
        /* Highlight the trigger button too */
        if (moreBtn) moreBtn.classList.add('active');
      }
    });
  }

  setActiveLinks();



  // Card number: format as "1234 5678 9012 3456"
  cardNumberInput.addEventListener('input', function () {
    let raw = this.value.replace(/\D/g, '').slice(0, 16);
    this.value = raw.replace(/(.{4})/g, '$1 ').trim();
  });

  // Expiry: auto-insert slash after MM
  expiryInput.addEventListener('input', function (e) {
    let raw = this.value.replace(/\D/g, '').slice(0, 4);
    if (raw.length >= 3) {
      this.value = raw.slice(0, 2) + '/' + raw.slice(2);
    } else {
      this.value = raw;
    }
  });

  /* ── Form submission ───────────────────────────────────────────── */
  cardForm.addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Payment processed successfully! Thank you.');
    this.reset();
  });

  /* ── Toast ─────────────────────────────────────────────────────── */
  let toastTimer = null;

  function showToast(message) {
    // Update text (keep the SVG icon, replace only the text node)
    const textNode = Array.from(toast.childNodes).find(n => n.nodeType === Node.TEXT_NODE);
    if (textNode) {
      textNode.textContent = ' ' + message;
    } else {
      toast.appendChild(document.createTextNode(' ' + message));
    }

    toast.classList.remove('hidden');

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.add('hidden'), 4000);
  }

  /* ── Entrance animations (IntersectionObserver) ────────────────── */
  const animatables = document.querySelectorAll('.animate-in');

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, i) => {
          if (entry.isIntersecting) {
            // Stagger delay based on order
            const delay = i * 80;
            setTimeout(() => entry.target.classList.add('visible'), delay);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    animatables.forEach(el => observer.observe(el));
  } else {
    // Fallback: just show everything
    animatables.forEach(el => el.classList.add('visible'));
  }

  /* ── Trigger animations on load ────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', () => {
    const hero = document.getElementById('hero');
    if (hero) hero.classList.add('visible');
  });

})();
