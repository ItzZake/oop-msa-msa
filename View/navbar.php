<link rel="stylesheet" href="css/navbar.css" />

<nav class="navbar" id="navbar">
    <div class="container navbar__inner">

      <!-- Logo -->
      <a href="Index.php" class="navbar__logo">
        <div class="navbar__logo-img">
          <img src="logo.jpeg" alt="Wellucation" onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:1.5rem\'>🌟</span>';" />
        </div>
        <div class="navbar__logo-text">
          <span class="navbar__logo-name">Wellucation</span>
          <span class="navbar__logo-tagline">Learn. Play. Grow</span>
        </div>
      </a>

      <!-- Desktop Links: 4 main + dropdown -->
      <div class="navbar__links" id="navLinks">
        <a href="index.php" class="nav-link active" data-path="/">Home</a>
        <a href="about.php" class="nav-link" data-path="/about">About Us</a>
        <a href="contact.php" class="nav-link" data-path="/contact">Contact Us</a>
        <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
          <a href="logout.php" class="nav-link" data-path="/logout">Logout</a>
        <?php else: ?>
          <a href="login.php" class="nav-link" data-path="/login">Login</a>
        <?php endif; ?>

        <!-- More dropdown -->
        <div class="nav-dropdown" id="moreDropdown">
          <button class="nav-link nav-dropdown__trigger" id="moreBtn" aria-expanded="false" aria-haspopup="true">
            More
            <svg class="nav-dropdown__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="nav-dropdown__menu" id="moreMenu" role="menu">
            <a href="Profile.php"     class="nav-dropdown__item" role="menuitem">👤 Profiles</a>
            <a href="dashboard.php"    class="nav-dropdown__item" role="menuitem">📊 Dashboard</a>
            <a href="Attendance.php"   class="nav-dropdown__item" role="menuitem">📅 Attendance</a>
            <a href="Assignments.php"  class="nav-dropdown__item" role="menuitem">📝 Assignments</a>
            <div class="nav-dropdown__divider"></div>
            <a href="payment.php"      class="nav-dropdown__item" role="menuitem">💳 Payment</a>
            <a href="subscription.php" class="nav-dropdown__item" role="menuitem">⭐ Subscription</a>
          </div>
        </div>
      </div>

      <!-- Right side -->
      <div class="navbar__right">
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          <svg class="icon-close hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
      <a href="" class="nav-link active" data-path="/">🏠 Home</a>
      <a href="about" class="nav-link" data-path="/about">ℹ️ About Us</a>
      <a href="contact" class="nav-link" data-path="/contact">📞 Contact Us</a>
      <a href="profile" class="nav-link" data-path="/profile">👤 Profiles</a>
      <a href="dashboard" class="nav-link" data-path="/dashboard">📊 Dashboard</a>
      <a href="attendance" class="nav-link" data-path="/attendance">📅 Attendance</a>
      <a href="reports" class="nav-link" data-path="/reports">📋 Reports</a>
      <a href="assignments" class="nav-link" data-path="/assignments">📝 Assignments</a>
      <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
        <a href="logout.php" class="nav-link" data-path="/logout">🔐 Logout</a>
      <?php else: ?>
        <a href="login.php" class="nav-link" data-path="/login">🔐 Login</a>
      <?php endif; ?>
      <a href="payment" class="nav-link" data-path="/payment">💳 Payment</a>
      <a href="subscription" class="nav-link" data-path="/subscription">⭐ Subscription</a>
      <a href="excuse" class="nav-link" data-path="/excuse">🙋 Excuse</a>
      <a href="messages" class="nav-link" data-path="/messages">💬 Messages</a>
      <a href="application" class="nav-link" data-path="/application">📄 Application</a>
      <a href="settings" class="nav-link" data-path="/settings">⚙️ Settings</a>

    </div>
  </nav>

  <script>
    /* ════════════════════════════════════════════
       NAVBAR DROPDOWN & MENU FUNCTIONALITY
    ════════════════════════════════════════════ */

    // Ensure DOM is ready before initializing
    function initNavbar() {
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
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initNavbar);
    } else {
      // DOM is already loaded
      initNavbar();
    }
  </script>