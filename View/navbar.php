<nav class="navbar">
  <div class="nav-inner">
    <a class="logo" href="index.php">
      <div class="logo-circle">🏫</div>
      <div class="logo-texts">
        <span class="logo-title">Wellucation</span>
        <span class="logo-sub">Learn. Play. Grow</span>
      </div>
    </a>
    <ul class="nav-links">
      <li><a href="index.php" <?php echo isset($currentPage) && $currentPage == 'home' ? 'class="active"' : ''; ?>>Home</a></li>
      <li><a href="about.php" <?php echo isset($currentPage) && $currentPage == 'about' ? 'class="active"' : ''; ?>>About Us</a></li>
      <li><a href="contact.php" <?php echo isset($currentPage) && $currentPage == 'contact' ? 'class="active"' : ''; ?>>Contact Us</a></li>
      <li><a href="dashboard.php" <?php echo isset($currentPage) && $currentPage == 'dashboard' ? 'class="active"' : ''; ?>>Dashboard</a></li>
      <li><a href="assignments.php" <?php echo isset($currentPage) && $currentPage == 'assignments' ? 'class="active"' : ''; ?>>Assignments</a></li>
      <li><a href="payment.php" <?php echo isset($currentPage) && $currentPage == 'payment' ? 'class="active"' : ''; ?>>Payment</a></li>
      <li><a href="messages.php" <?php echo isset($currentPage) && $currentPage == 'messages' ? 'class="active"' : ''; ?>>Messages</a></li>
      <li><a href="login.php" <?php echo isset($currentPage) && $currentPage == 'login' ? 'class="active"' : ''; ?>>Login</a></li>
      <li><a href="register.php" <?php echo isset($currentPage) && $currentPage == 'register' ? 'class="active"' : ''; ?>>Register</a></li>
    </ul>
    <div class="navbar-actions">
      <a href="enroll.php" class="btn-enroll">🌟 Enroll Now</a>
      <button class="hamburger" onclick="toggleMobileMenu()" id="hamburgerBtn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
    </div>
  </div>
  <div class="mobile-nav" id="mobileNav">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact Us</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="assignments.php">Assignments</a>
    <a href="payment.php">Payment</a>
    <a href="messages.php">Messages</a>
    <a href="login.php">Login</a>
    <a href="enroll.php">Enroll Now</a>
    <a href="subscription.php">Subscription</a>
    <a href="excuse.php">Excuse</a>
    <a href="application.php">Application</a>
    <a href="settings.php">Settings</a>
  </div>
</nav>
