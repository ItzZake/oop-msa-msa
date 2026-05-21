/* ══════════════════════════════════════
   WELLUCATION — CONTACT PAGE SCRIPTS
   NOTE: Navbar functionality is now handled by navbar.php
   ══════════════════════════════════════ */

/* ── Contact form submission ── */
const contactForm   = document.getElementById('contactForm');
const successBanner = document.getElementById('successBanner');

contactForm.addEventListener('submit', (e) => {
  e.preventDefault();

  /* Show success banner */
  successBanner.classList.add('show');
  successBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

  /* Reset form */
  contactForm.reset();

  /* Hide banner after 4 seconds */
  setTimeout(() => {
    successBanner.classList.remove('show');
  }, 4000);
});

/* ── Intersection Observer: trigger animations on scroll ── */
const animatedEls = document.querySelectorAll(
  '.animate-slide-up, .animate-from-left, .animate-from-right'
);

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.animationPlayState = 'running';
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.15 }
);

animatedEls.forEach((el) => {
  /* Pause until visible */
  el.style.animationPlayState = 'paused';
  observer.observe(el);
});