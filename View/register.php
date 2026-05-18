<?php
session_start();
$pageTitle = "Register – Wellucation Nursery";
$currentPage = "register";
$pageCss = 'login.css';
include 'header.php';
include 'navbar.php';
?>

<div class="login-page">
  <div class="login-hero">
    <div class="blob blob-pink login-blob"></div>
    <div class="blob blob-blue login-blob"></div>
    <div style="position:absolute;top:5rem;right:25%;font-size:3rem;" class="bounce">⭐</div>
    <div style="position:absolute;bottom:8rem;left:25%;font-size:2.5rem;" class="bounce">🌈</div>
    <div class="login-hero-content">
      <div class="text-center mb-6">
        <h1 class="login-title">Create your Wellucation account</h1>
        <p class="login-subtitle">Join us and manage nursery activity smoothly.</p>
      </div>
      <div class="login-card">
        <?php if (isset($_SESSION['error'])): ?>
        <div class="login-alert login-alert-error">
          <div>❌ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
        <div class="login-alert login-alert-success">
          <div>✅ <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
        </div>
        <?php endif; ?>
        <div class="login-tabs" id="loginTabs">
          <button id="loginTabBtn" class="login-tab inactive" onclick="switchLoginTab('login')">🔑 Login</button>
          <button id="registerTabBtn" class="login-tab active" onclick="switchLoginTab('register')">➕ Register</button>
        </div>
        <form id="loginForm" class="login-form is-hidden" method="POST" action="authenticate.php">
          <div class="form-group"><label class="form-label">Email Address</label><input type="email" name="email" required class="form-input" placeholder="your.email@example.com"></div>
          <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" required class="form-input" placeholder="••••••••"></div>
          <div class="login-helper-row">
            <label class="login-checkbox"><input type="checkbox" name="remember"> Remember me</label>
            <a href="forgot-password.php" class="login-link">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary login-submit">Sign In →</button>
        </form>
        <form id="registerForm" class="login-form" method="POST" action="register_user.php">
          <input type="hidden" name="redirect" value="register.php">
          <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="fullname" required class="form-input" placeholder="John Doe"></div>
          <div class="form-group"><label class="form-label">Email Address</label><input type="email" name="email" required class="form-input" placeholder="your.email@example.com"></div>
          <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" required class="form-input" placeholder="••••••••"></div>
          <div class="form-group"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" required class="form-input" placeholder="••••••••"></div>
          <div class="login-terms"><label class="login-checkbox"><input type="checkbox" name="terms" required> I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></label></div>
          <button type="submit" class="btn btn-secondary login-submit">Create Account →</button>
        </form>
        <p class="login-footer">Need help? <a href="contact.php">Contact Support</a></p>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
