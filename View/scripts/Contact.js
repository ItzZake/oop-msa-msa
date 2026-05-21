/* ══════════════════════════════════════
   WELLUCATION — CONTACT PAGE SCRIPTS
<<<<<<< HEAD
   ══════════════════════════════════════ */

/* ── Navbar: sticky shadow ── */
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.style.boxShadow = window.scrollY > 8
    ? '0 4px 20px rgba(0,0,0,.12)'
    : '0 2px 12px rgba(0,0,0,.06)';
});

/* ── Hamburger / mobile menu ── */
const hamburger   = document.getElementById('hamburger');
const mobileMenu  = document.getElementById('mobileMenu');
const iconMenu    = hamburger.querySelector('.icon-menu');
const iconClose   = hamburger.querySelector('.icon-close');

hamburger.addEventListener('click', () => {
  const isOpen = mobileMenu.classList.toggle('open');
  hamburger.setAttribute('aria-expanded', isOpen);
  mobileMenu.setAttribute('aria-hidden', !isOpen);
  iconMenu.classList.toggle('hidden', isOpen);
  iconClose.classList.toggle('hidden', !isOpen);
});

/* Close mobile menu on outside click */
document.addEventListener('click', (e) => {
  if (!navbar.contains(e.target)) {
    mobileMenu.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    mobileMenu.setAttribute('aria-hidden', 'true');
    iconMenu.classList.remove('hidden');
    iconClose.classList.add('hidden');
  }
});

/* ── "More" dropdown ── */
const moreBtn  = document.getElementById('moreBtn');
const moreMenu = document.getElementById('moreMenu');

moreBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  const isOpen = moreMenu.classList.toggle('open');
  moreBtn.setAttribute('aria-expanded', isOpen);
});

document.addEventListener('click', (e) => {
  if (!moreMenu.contains(e.target) && e.target !== moreBtn) {
    moreMenu.classList.remove('open');
    moreBtn.setAttribute('aria-expanded', 'false');
  }
});

=======
   NOTE: Navbar functionality is now handled by navbar.php
   ══════════════════════════════════════ */

>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
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