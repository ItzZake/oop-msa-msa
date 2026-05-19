/* ════════════════════════════════════════════
   WELLUCATION — About.js
   Handles: navbar scroll/sticky, hamburger menu,
   "More" dropdown, and scroll-reveal animations.
════════════════════════════════════════════ */

(function () {
  'use strict';

  /* ─────────────────────────────────────────
     1. NAVBAR — shrink on scroll + shadow
  ───────────────────────────────────────── */
  const navbar = document.getElementById('navbar');

  function onNavbarScroll() {
    if (window.scrollY > 20) {
      navbar.classList.add('navbar--scrolled');
    } else {
      navbar.classList.remove('navbar--scrolled');
    }
  }

  window.addEventListener('scroll', onNavbarScroll, { passive: true });
  onNavbarScroll(); // run once on load in case page is already scrolled


  /* ─────────────────────────────────────────
     2. HAMBURGER — mobile menu toggle
  ───────────────────────────────────────── */
  const hamburger   = document.getElementById('hamburger');
  const mobileMenu  = document.getElementById('mobileMenu');
  const iconMenu    = hamburger?.querySelector('.icon-menu');
  const iconClose   = hamburger?.querySelector('.icon-close');

  function openMobileMenu() {
    mobileMenu.classList.add('mobile-menu--open');
    mobileMenu.setAttribute('aria-hidden', 'false');
    hamburger.setAttribute('aria-expanded', 'true');
    iconMenu?.classList.add('hidden');
    iconClose?.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // prevent background scroll
  }

  function closeMobileMenu() {
    mobileMenu.classList.remove('mobile-menu--open');
    mobileMenu.setAttribute('aria-hidden', 'true');
    hamburger.setAttribute('aria-expanded', 'false');
    iconMenu?.classList.remove('hidden');
    iconClose?.classList.add('hidden');
    document.body.style.overflow = '';
  }

  hamburger?.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.contains('mobile-menu--open');
    isOpen ? closeMobileMenu() : openMobileMenu();
  });

  // Close mobile menu when a nav link inside it is clicked
  mobileMenu?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', closeMobileMenu);
  });

  // Close mobile menu on outside click
  document.addEventListener('click', (e) => {
    if (
      mobileMenu?.classList.contains('mobile-menu--open') &&
      !mobileMenu.contains(e.target) &&
      !hamburger.contains(e.target)
    ) {
      closeMobileMenu();
    }
  });

  // Close mobile menu on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeMobileMenu();
      closeMoreDropdown();
    }
  });


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


  /* ─────────────────────────────────────────
     4. SCROLL-REVEAL — replaces Framer Motion
     Elements with .reveal animate in when
     they enter the viewport.
  ───────────────────────────────────────── */
  const revealElements = document.querySelectorAll('.reveal');

  // Stagger sibling .reveal cards inside the same grid/flex parent
  function applyStaggerDelays() {
    const staggerParents = document.querySelectorAll(
      '.about-mv__grid, .values__grid, .staff__grid, .timeline'
    );

    staggerParents.forEach(parent => {
      const children = parent.querySelectorAll(':scope > .reveal');
      children.forEach((child, i) => {
        child.style.transitionDelay = `${i * 80}ms`;
      });
    });
  }

  applyStaggerDelays();

  // IntersectionObserver — triggers .visible class
  const revealObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          // Unobserve after reveal (once: true equivalent)
          revealObserver.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.12,       // 12% of element must be visible
      rootMargin: '0px 0px -40px 0px', // trigger slightly before bottom of viewport
    }
  );

  revealElements.forEach(el => revealObserver.observe(el));

  // Hero section — trigger immediately (no scroll needed for above-the-fold)
  const heroReveal = document.querySelector('.about-hero .reveal');
  if (heroReveal) {
    // Small delay so CSS transition plays visibly on load
    setTimeout(() => heroReveal.classList.add('visible'), 100);
  }


  /* ─────────────────────────────────────────
     5. TIMELINE LINE — animated draw-in
     Draws the vertical gradient line as the
     user scrolls through the timeline section.
  ───────────────────────────────────────── */
  const timelineLine = document.querySelector('.timeline__line');

  if (timelineLine) {
    timelineLine.style.transformOrigin = 'top center';
    timelineLine.style.transform = 'scaleY(0)';
    timelineLine.style.transition = 'transform 1.2s cubic-bezier(0.22, 1, 0.36, 1)';

    const lineObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            timelineLine.style.transform = 'scaleY(1)';
            lineObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.05 }
    );

    lineObserver.observe(timelineLine);
  }


  /* ─────────────────────────────────────────
     6. VALUE CARDS — hover lift
     (CSS handles .value-card:hover, but we
     add focus-visible support for accessibility)
  ───────────────────────────────────────── */
  document.querySelectorAll('.value-card, .staff-card').forEach(card => {
    card.setAttribute('tabindex', '0');

    card.addEventListener('focus', () => {
      card.style.transform = 'translateY(-4px)';
      card.style.boxShadow = '0 12px 32px rgba(0,0,0,0.1)';
    });

    card.addEventListener('blur', () => {
      card.style.transform = '';
      card.style.boxShadow = '';
    });
  });


  /* ─────────────────────────────────────────
     7. SMOOTH SCROLL — anchor links
  ───────────────────────────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const targetId = anchor.getAttribute('href').slice(1);
      const target = document.getElementById(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });


  /* ─────────────────────────────────────────
     8. CTA BUTTON — pulse animation on hover
  ───────────────────────────────────────── */
  const ctaBtn = document.querySelector('.about-cta__btn');

  ctaBtn?.addEventListener('mouseenter', () => {
    ctaBtn.style.transform = 'scale(1.05)';
  });

  ctaBtn?.addEventListener('mouseleave', () => {
    ctaBtn.style.transform = '';
  });

})();