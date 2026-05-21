/* ════════════════════════════════════════════
<<<<<<< HEAD
   WELLUCATION — main.js
════════════════════════════════════════════ */

(function () {
  'use strict';

  /* ── Element refs ── */
  const hamburger   = document.getElementById('hamburger');
  const mobileMenu  = document.getElementById('mobileMenu');
  const iconMenu    = hamburger?.querySelector('.icon-menu');
  const iconClose   = hamburger?.querySelector('.icon-close');
  const moreBtn     = document.getElementById('moreBtn');
  const moreDropdown = document.getElementById('moreDropdown');

  /* ══════════════════════════════════════════
     HAMBURGER — mobile menu toggle
  ══════════════════════════════════════════ */
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      const isOpen = mobileMenu.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', isOpen);
      mobileMenu.setAttribute('aria-hidden', !isOpen);
      iconMenu?.classList.toggle('hidden', isOpen);
      iconClose?.classList.toggle('hidden', !isOpen);
    });
  }

  /* ══════════════════════════════════════════
     DROPDOWN — "More" menu
  ══════════════════════════════════════════ */
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

  /* ══════════════════════════════════════════
     NAVBAR SCROLL SHADOW
  ══════════════════════════════════════════ */
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 8) {
        navbar.style.boxShadow = '0 4px 16px rgba(0,0,0,0.10)';
      } else {
        navbar.style.boxShadow = '0 1px 6px rgba(0,0,0,0.06)';
      }
    }, { passive: true });
  }

  /* ══════════════════════════════════════════
     CLOSE MOBILE MENU on nav-link click
  ══════════════════════════════════════════ */
  if (mobileMenu) {
    mobileMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mobileMenu.classList.remove('open');
        mobileMenu.setAttribute('aria-hidden', 'true');
        hamburger?.setAttribute('aria-expanded', 'false');
        iconMenu?.classList.remove('hidden');
        iconClose?.classList.add('hidden');
      });
    });
  }

})();
=======
   WELLUCATION — home.js
════════════════════════════════════════════
   
   NOTE: Navbar functionality is now handled by navbar.php
   This file previously contained duplicate navbar code which
   has been removed to prevent conflicts.
   
════════════════════════════════════════════ */

// Home page specific functionality can be added here
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
