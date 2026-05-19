<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Wellucation</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&family=Fredoka+One&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login.css" />
</head>
<body>

  <!-- ══════════════ TOP BAR ══════════════ -->
  <div class="topbar">
    <div class="container topbar__inner">
      <div class="topbar__left">
        <span class="topbar__item">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"/></svg>
          +1 (555) 123-4567
        </span>
        <span class="topbar__item">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          hello@wellucation.edu
        </span>
      </div>
      <div class="topbar__right">
        <span>Follow us:</span>
        <a href="#" class="topbar__social" aria-label="Facebook">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="#" class="topbar__social" aria-label="Instagram">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
        </a>
        <a href="#" class="topbar__social" aria-label="Twitter">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
        </a>
        <a href="#" class="topbar__social" aria-label="YouTube">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><polygon points="10 15 15 12 10 9 10 15"/></svg>
        </a>
      </div>
    </div>
  </div>

  <!-- ══════════════ NAVBAR ══════════════ -->
  <nav class="navbar" id="navbar">
    <div class="container navbar__inner">
      <a href="Home.html" class="navbar__logo">
        <div class="navbar__logo-img">
          <img src="logo.jpeg" alt="Wellucation" onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:1.5rem\'>🌟</span>';" />
        </div>
        <div class="navbar__logo-text">
          <span class="navbar__logo-name">Wellucation</span>
          <span class="navbar__logo-tagline">Learn. Play. Grow</span>
        </div>
      </a>

      <div class="navbar__links" id="navLinks">
        <a href="Home.html" class="nav-link" data-path="/">Home</a>
        <a href="About.html" class="nav-link" data-path="/about">About Us</a>
        <a href="contact.html" class="nav-link" data-path="/contact">Contact Us</a>
        <a href="login.html" class="nav-link active" data-path="/login">Login</a>

        <div class="nav-dropdown" id="moreDropdown">
          <button class="nav-link nav-dropdown__trigger" id="moreBtn" aria-expanded="false" aria-haspopup="true">
            More
            <svg class="nav-dropdown__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="nav-dropdown__menu" id="moreMenu" role="menu">
            <a href="profiles.html"     class="nav-dropdown__item" role="menuitem">👤 Profiles</a>
            <a href="dashboard.html"    class="nav-dropdown__item" role="menuitem">📊 Dashboard</a>
            <a href="attendance.html"   class="nav-dropdown__item" role="menuitem">📅 Attendance</a>
            <a href="reports.html"      class="nav-dropdown__item" role="menuitem">📋 Reports</a>
            <a href="assignments.html"  class="nav-dropdown__item" role="menuitem">📝 Assignments</a>
            <div class="nav-dropdown__divider"></div>
            <a href="payment.html"      class="nav-dropdown__item" role="menuitem">💳 Payment</a>
            <a href="subscription.html" class="nav-dropdown__item" role="menuitem">⭐ Subscription</a>
            <a href="excuse.html"       class="nav-dropdown__item" role="menuitem">🙋 Excuse</a>
            <a href="messages.html"     class="nav-dropdown__item" role="menuitem">💬 Messages</a>
            <a href="application.html"  class="nav-dropdown__item" role="menuitem">📄 Application</a>
            <a href="settings.html"     class="nav-dropdown__item" role="menuitem">⚙️ Settings</a>
          </div>
        </div>
      </div>

      <div class="navbar__right">
        <a href="enroll.html" class="btn-enroll btn-enroll--desktop">🌟 Enroll Now</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          <svg class="icon-close hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
    </div>

    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
      <a href="index.html"  class="nav-link" data-path="/">🏠 Home</a>
      <a href="about.html"  class="nav-link" data-path="/about">ℹ️ About Us</a>
      <a href="contact.html" class="nav-link" data-path="/contact">📞 Contact Us</a>
      <a href="profiles.html" class="nav-link" data-path="/profiles">👤 Profiles</a>
      <a href="dashboard.html" class="nav-link" data-path="/dashboard">📊 Dashboard</a>
      <a href="attendance.html" class="nav-link" data-path="/attendance">📅 Attendance</a>
      <a href="reports.html" class="nav-link" data-path="/reports">📋 Reports</a>
      <a href="assignments.html" class="nav-link" data-path="/assignments">📝 Assignments</a>
      <a href="login.html"  class="nav-link active" data-path="/login">🔐 Login</a>
      <a href="payment.html" class="nav-link" data-path="/payment">💳 Payment</a>
      <a href="subscription.html" class="nav-link" data-path="/subscription">⭐ Subscription</a>
      <a href="excuse.html" class="nav-link" data-path="/excuse">🙋 Excuse</a>
      <a href="messages.html" class="nav-link" data-path="/messages">💬 Messages</a>
      <a href="application.html" class="nav-link" data-path="/application">📄 Application</a>
      <a href="settings.html" class="nav-link" data-path="/settings">⚙️ Settings</a>
      <a href="enroll.html" class="btn-enroll btn-enroll--mobile">🌟 Enroll Now</a>
    </div>
  </nav>

  <!-- ══════════════ MAIN LOGIN CONTENT ══════════════ -->
  <main class="login-hero">
    <!-- Decorative corner emojis -->
    <span class="login-hero__deco login-hero__deco--1">⭐</span>
    <span class="login-hero__deco login-hero__deco--2">🌈</span>

    <div class="login-hero__inner reveal">
      <h1 class="login-hero__title">Welcome to Wellucation</h1>
      <p class="login-hero__subtitle">Sign in to access your account or create a new one</p>

      <div class="login-card">
        <!-- Tabs -->
        <div class="tabs">
          <button class="tab-btn tab-btn--active" id="tabLogin" data-tab="login">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            Login
          </button>
          <button class="tab-btn" id="tabRegister" data-tab="register">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Register
          </button>
        </div>

        <!-- Login Form -->
        <div class="tab-panel tab-panel--active" id="panelLogin">
          <form id="loginForm" novalidate>
            <div class="field">
              <label class="field__label" for="loginEmail">Email Address</label>
              <div class="field__wrap">
                <svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <input id="loginEmail" class="field__input" type="email" placeholder="your.email@example.com" required />
              </div>
            </div>

            <div class="field">
              <label class="field__label" for="loginPassword">Password</label>
              <div class="field__wrap">
                <svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input id="loginPassword" class="field__input field__input--padded-right" type="password" placeholder="••••••••" required />
                <button type="button" class="field__eye" id="toggleLoginPw" aria-label="Toggle password visibility">
                  <svg class="eye-icon eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  <svg class="eye-icon eye-off hidden" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                </button>
              </div>
            </div>

            <div class="login-meta">
              <label class="checkbox-label">
                <input type="checkbox" class="checkbox" />
                <span>Remember me</span>
              </label>
              <a href="#" class="link-pink">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit btn-submit--pink">
              Sign In
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
          </form>
        </div>

        <!-- Register Form -->
        <div class="tab-panel" id="panelRegister">
          <form id="registerForm" novalidate>
            <div class="field">
              <label class="field__label" for="regName">Full Name</label>
              <div class="field__wrap">
                <svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                <input id="regName" class="field__input" type="text" placeholder="John Doe" required />
              </div>
            </div>

            <div class="field">
              <label class="field__label" for="regEmail">Email Address</label>
              <div class="field__wrap">
                <svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <input id="regEmail" class="field__input" type="email" placeholder="your.email@example.com" required />
              </div>
            </div>

            <div class="field">
              <label class="field__label" for="regPassword">Password</label>
              <div class="field__wrap">
                <svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input id="regPassword" class="field__input field__input--padded-right" type="password" placeholder="••••••••" required />
                <button type="button" class="field__eye" id="toggleRegPw" aria-label="Toggle password visibility">
                  <svg class="eye-icon eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  <svg class="eye-icon eye-off hidden" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                </button>
              </div>
            </div>

            <div class="field">
              <label class="field__label" for="regConfirm">Confirm Password</label>
              <div class="field__wrap">
                <svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input id="regConfirm" class="field__input" type="password" placeholder="••••••••" required />
              </div>
            </div>

            <div class="field">
              <label class="checkbox-label checkbox-label--start">
                <input type="checkbox" class="checkbox" id="agreeTerms" required />
                <span>
                  I agree to the
                  <a href="#" class="link-pink">Terms &amp; Conditions</a>
                  and
                  <a href="#" class="link-pink">Privacy Policy</a>
                </span>
              </label>
            </div>

            <button type="submit" class="btn-submit btn-submit--blue">
              Create Account
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
          </form>
        </div>

        <div class="login-footer">
          <p>Need help? <a href="#" class="link-pink">Contact Support</a></p>
        </div>
      </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="toast" role="alert" aria-live="polite"></div>
  </main>

    <!-- ══════════════ FOOTER ══════════════ -->
  <footer class="footer">
    <div class="container footer__grid">

      <!-- Brand -->
      <div class="footer__col">
        <div class="footer__brand">
          <div class="footer__logo-img">
            <img src="logo.jpeg" alt="Wellucation" onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:1.4rem\'>🌟</span>';" />
          </div>
          <div>
            <div class="footer__brand-name">Wellucation</div>
            <div class="footer__brand-tagline">Learn. Play. Grow</div>
          </div>
        </div>
        <p class="footer__desc">
          Nurturing young minds with love, creativity, and excellence in early childhood education.
        </p>
        <div class="footer__socials">
          <a href="#" class="footer__social" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="footer__social" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
          </a>
          <a href="#" class="footer__social" aria-label="Twitter">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
          </a>
          <a href="#" class="footer__social" aria-label="YouTube">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><polygon points="10 15 15 12 10 9 10 15"/></svg>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer__col">
        <h4 class="footer__heading">Quick Links</h4>
        <ul class="footer__list">
          <li><a href="Home.html"            class="footer__link"><span class="footer__arrow">›</span> Home</a></li>
          <li><a href="About.html"       class="footer__link"><span class="footer__arrow">›</span> About Us</a></li>
          <li><a href="Contact.html"     class="footer__link"><span class="footer__arrow">›</span> Contact Us</a></li>
          <li><a href="Profiles.html"    class="footer__link"><span class="footer__arrow">›</span> Profiles</a></li>
          <li><a href="Dashboard.html"   class="footer__link"><span class="footer__arrow">›</span> Dashboard</a></li>
          <li><a href="Attendance.html"  class="footer__link"><span class="footer__arrow">›</span> Attendance</a></li>
          <li><a href="Reports.html"     class="footer__link"><span class="footer__arrow">›</span> Reports</a></li>
          <li><a href="Assignments.html" class="footer__link"><span class="footer__arrow">›</span> Assignments</a></li>
          <li><a href="Enroll.html"      class="footer__link"><span class="footer__arrow">›</span> Enroll Now</a></li>
          <li><a href="Login.html"       class="footer__link"><span class="footer__arrow">›</span> Login</a></li>
          <li><a href="Payment.html"     class="footer__link"><span class="footer__arrow">›</span> Payment</a></li>
          <li><a href="Messages.html"    class="footer__link"><span class="footer__arrow">›</span> Messages</a></li>
          <li><a href="Settings.html"    class="footer__link"><span class="footer__arrow">›</span> Settings</a></li>
        </ul>
      </div>

      <!-- Programs -->
      <div class="footer__col">
        <h4 class="footer__heading">Our Programs</h4>
        <ul class="footer__list">
          <li class="footer__item"><span class="footer__arrow">›</span> Nursery (Ages 2–3)</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Kindergarten 1 (Ages 3–4)</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Kindergarten 2 (Ages 4–5)</li>
          <li class="footer__item"><span class="footer__arrow">›</span> After School Care</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Summer Camp</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Special Needs Support</li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="footer__col">
        <h4 class="footer__heading">Get In Touch</h4>
        <div class="footer__contact">
          <div class="footer__contact-row">
            <svg class="footer__contact-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>123 Sunshine Lane, Kidstown, CA 90210</span>
          </div>
          <div class="footer__contact-row">
            <svg class="footer__contact-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"/></svg>
            <span>+1 (555) 123-4567</span>
          </div>
          <div class="footer__contact-row">
            <svg class="footer__contact-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>hello@wellucation.edu</span>
          </div>
        </div>
        <div class="footer__hours">
          <p class="footer__hours-label">School Hours</p>
          <p class="footer__hours-main">Mon – Fri: 7:00 AM – 6:00 PM</p>
          <p class="footer__hours-sub">Sat: 8:00 AM – 2:00 PM</p>
        </div>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="footer__bottom">
      <div class="container footer__bottom-inner">
        <p class="footer__copy">© 2026 Wellucation Nursery. All rights reserved.</p>
        <p class="footer__made-with">
          Made with
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#E91E8C" stroke="#E91E8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
          for little learners
        </p>
      </div>
    </div>
  </footer>

  <script src="../scripts/login.js"></script>
</body>
</html>