/* ── PaymentPage – main.js ─────────────────────────────────────────
<<<<<<< HEAD
   Converted from PaymentPage.tsx (React → vanilla JS)
=======
   Sends payment form data to controllers/PaymentController.php
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
   ─────────────────────────────────────────────────────────────── */

(function () {
  'use strict';

  /* ── State ─────────────────────────────────────────────────────── */
  let paymentMethod = 'card';

<<<<<<< HEAD
  /* ── Element refs ──────────────────────────────────────────────── */
  const methodOptions = document.querySelectorAll('.method-option');
  const cardForm      = document.getElementById('cardForm');
  const paypalPanel   = document.getElementById('paypalPanel');
  const toast         = document.getElementById('toast');

  const cardNumberInput = document.getElementById('cardNumber');
  const expiryInput     = document.getElementById('expiry');
=======
  /* ── Order line items (must match your Order Summary in HTML) ──── */
  const lineItems = [
    { label: 'Monthly Tuition',      amount:  850.00 },
    { label: 'Registration Fee',     amount:  150.00 },
    { label: 'Activity Materials',   amount:   50.00 },
    { label: 'Early Bird Discount',  amount: -100.00 }
  ];
  const orderTotal = lineItems.reduce((sum, item) => sum + item.amount, 0); // 950.00

  /* ── Element refs ──────────────────────────────────────────────── */
  const methodOptions   = document.querySelectorAll('.method-option');
  const cardForm        = document.getElementById('cardForm');
  const paypalPanel     = document.getElementById('paypalPanel');
  const toast           = document.getElementById('toast');
  const cardNumberInput = document.getElementById('cardNumber');
  const expiryInput     = document.getElementById('expiry');
  const payBtn          = cardForm.querySelector('.pay-btn');
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd

  /* ── Payment method selection ──────────────────────────────────── */
  function setMethod(method) {
    paymentMethod = method;
<<<<<<< HEAD

    methodOptions.forEach(opt => {
      opt.classList.toggle('active', opt.dataset.method === method);
    });

=======
    methodOptions.forEach(opt => {
      opt.classList.toggle('active', opt.dataset.method === method);
    });
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
    const isCard = method === 'card' || method === 'debit';
    cardForm.classList.toggle('hidden', !isCard);
    paypalPanel.classList.toggle('hidden', isCard);
  }

<<<<<<< HEAD
  methodOptions.forEach(opt => {
    opt.addEventListener('click', () => setMethod(opt.dataset.method));
  });

  // Initialise active state
  setMethod('card');

  /* ── Input formatting ──────────────────────────────────────────── */

  // Card number: format as "1234 5678 9012 3456"
=======
  methodOptions.forEach(opt => opt.addEventListener('click', () => setMethod(opt.dataset.method)));
  setMethod('card');

  /* ── Input formatting ──────────────────────────────────────────── */
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
  cardNumberInput.addEventListener('input', function () {
    let raw = this.value.replace(/\D/g, '').slice(0, 16);
    this.value = raw.replace(/(.{4})/g, '$1 ').trim();
  });

<<<<<<< HEAD
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

=======
  expiryInput.addEventListener('input', function () {
    let raw = this.value.replace(/\D/g, '').slice(0, 4);
    this.value = raw.length >= 3 ? raw.slice(0, 2) + '/' + raw.slice(2) : raw;
  });

  /* ── Map payment method → DB gateway ENUM ──────────────────────── */
  // DB accepts: 'Paymob', 'Fawry', 'ValU'
  // card/debit → Paymob, paypal → Fawry (adjust as needed)
  function getGateway(method) {
    const map = { card: 'Paymob', debit: 'Paymob', paypal: 'Fawry' };
    return map[method] || 'Paymob';
  }

  /* ── Form submission ───────────────────────────────────────────── */
  cardForm.addEventListener('submit', function (e) {
    e.preventDefault();

    // Basic client-side validation
    const cardNumber = document.getElementById('cardNumber').value.trim();
    const cardName   = document.getElementById('cardName').value.trim();
    const expiry     = document.getElementById('expiry').value.trim();
    const cvv        = document.getElementById('cvv').value.trim();

    if (!cardNumber || !cardName || !expiry || !cvv) {
      showToast('❌ Please fill in all card fields.', false);
      return;
    }

    // Get Parent ID and Child ID from the hidden inputs in HTML
    // (add these inputs to your HTML, or hardcode for testing)
    const parentID = parseInt(document.getElementById('parentID')?.value) || 0;
    const childID  = parseInt(document.getElementById('childID')?.value)  || 0;

    if (parentID < 1 || childID < 1) {
      showToast('❌ Parent ID and Child ID are required.', false);
      return;
    }

    // Disable button while processing
    payBtn.disabled = true;
    payBtn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
      Processing...`;

    // Send to PaymentController.php
    fetch('../../Controller/PaymentController.php?action=process', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        parentID:     parentID,
        childID:      childID,
        planName:     'Basic',       // update if you pass a plan name from subscription page
        billingCycle: 'Monthly',
        gateway:      getGateway(paymentMethod),
        gatewayTxID:  'TXN-' + Date.now(), // simulated transaction ID
        lineItems:    lineItems
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showToast('✅ ' + data.message, true);
        cardForm.reset();
      } else {
        showToast('❌ ' + data.message, false);
      }
    })
    .catch(() => {
      showToast('❌ Could not reach server. Is XAMPP running?', false);
    })
    .finally(() => {
      payBtn.disabled = false;
      payBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="11" height="11" x="11" y="11" rx="2" ry="2"/><path d="M7 7H4a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2v-3"/><path d="m14 4 3 3-7 7"/></svg>
        Pay Securely`;
    });
  });

  /* ── PayPal button ─────────────────────────────────────────────── */
  const paypalBtn = document.querySelector('.paypal-btn');
  if (paypalBtn) {
    paypalBtn.addEventListener('click', function () {
      showToast('🔄 Redirecting to PayPal...', true);
    });
  }

  /* ── Toast ─────────────────────────────────────────────────────── */
  let toastTimer = null;

  function showToast(message, success = true) {
    const icon = success
      ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;
    toast.innerHTML = icon + ' ' + message;
    toast.classList.remove('hidden');
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.add('hidden'), 4000);
  }

<<<<<<< HEAD
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

=======
  /* ── Entrance animations ───────────────────────────────────────── */
  const animatables = document.querySelectorAll('.animate-in');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          setTimeout(() => entry.target.classList.add('visible'), i * 80);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    animatables.forEach(el => observer.observe(el));
  } else {
    animatables.forEach(el => el.classList.add('visible'));
  }

>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
})();
