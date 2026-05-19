<head>
	<link rel="stylesheet" href="../view/css/navbar.css" />
</head>
<nav class="navbar" id="navbar">
    <div class="container navbar__inner">

      <!-- Logo -->
      <a href="Home.html" class="navbar__logo">
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
        <a href="login.php" class="nav-link" data-path="/login">Login</a>

        <!-- More dropdown -->
        <div class="nav-dropdown" id="moreDropdown">
          <button class="nav-link nav-dropdown__trigger" id="moreBtn" aria-expanded="false" aria-haspopup="true">
            More
            <svg class="nav-dropdown__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="nav-dropdown__menu" id="moreMenu" role="menu">
            <a href="profiles.php"     class="nav-dropdown__item" role="menuitem">👤 Profiles</a>
            <a href="dashboard.php"    class="nav-dropdown__item" role="menuitem">📊 Dashboard</a>
            <a href="attendance.php"   class="nav-dropdown__item" role="menuitem">📅 Attendance</a>
            <a href="reports.php"      class="nav-dropdown__item" role="menuitem">📋 Reports</a>
            <a href="assignments.php"  class="nav-dropdown__item" role="menuitem">📝 Assignments</a>
            <div class="nav-dropdown__divider"></div>
            <a href="payment.php"      class="nav-dropdown__item" role="menuitem">💳 Payment</a>
            <a href="subscription.php" class="nav-dropdown__item" role="menuitem">⭐ Subscription</a>
            <a href="excuse.php"       class="nav-dropdown__item" role="menuitem">🙋 Excuse</a>
            <a href="messages.php"     class="nav-dropdown__item" role="menuitem">💬 Messages</a>
            <a href="application.php"  class="nav-dropdown__item" role="menuitem">📄 Application</a>
            <a href="settings.php"     class="nav-dropdown__item" role="menuitem">⚙️ Settings</a>
          </div>
        </div>
      </div>

      <!-- Right side -->
      <div class="navbar__right">
        <a href="enroll.php" class="btn-enroll btn-enroll--desktop">🌟 Enroll Now</a>
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
      <a href="profiles" class="nav-link" data-path="/profiles">👤 Profiles</a>
      <a href="dashboard" class="nav-link" data-path="/dashboard">📊 Dashboard</a>
      <a href="attendance" class="nav-link" data-path="/attendance">📅 Attendance</a>
      <a href="reports" class="nav-link" data-path="/reports">📋 Reports</a>
      <a href="assignments" class="nav-link" data-path="/assignments">📝 Assignments</a>
      <a href="login" class="nav-link" data-path="/login">🔐 Login</a>
      <a href="payment" class="nav-link" data-path="/payment">💳 Payment</a>
      <a href="subscription" class="nav-link" data-path="/subscription">⭐ Subscription</a>
      <a href="excuse" class="nav-link" data-path="/excuse">🙋 Excuse</a>
      <a href="messages" class="nav-link" data-path="/messages">💬 Messages</a>
      <a href="application" class="nav-link" data-path="/application">📄 Application</a>
      <a href="settings" class="nav-link" data-path="/settings">⚙️ Settings</a>
      <a href="enroll.php" class="btn-enroll btn-enroll--mobile">🌟 Enroll Now</a>
    </div>
  </nav>