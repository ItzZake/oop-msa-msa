FILE 1: header.php
php
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo htmlspecialchars($pageTitle ?? 'Wellucation Nursery'); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<!-- TOP BAR -->
<div class="top-bar">
  <div class="container top-bar-inner">
    <div class="top-bar-left">
      <span>📞 +1 (555) 123-4567</span>
      <span>✉️ hello@wellucation.edu</span>
    </div>
    <div class="top-bar-right">
      <span>Follow us:</span>
      <a href="#">f</a><a href="#">@</a><a href="#">𝕏</a><a href="#">▶</a>
    </div>
  </div>
</div>
FILE 2: navbar.php
php
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
      <li><a href="index.php" <?php echo ($currentPage == 'home') ? 'class="active"' : ''; ?>>Home</a></li>
      <li><a href="about.php" <?php echo ($currentPage == 'about') ? 'class="active"' : ''; ?>>About Us</a></li>
      <li><a href="contact.php" <?php echo ($currentPage == 'contact') ? 'class="active"' : ''; ?>>Contact Us</a></li>
      <li><a href="profiles.php" <?php echo ($currentPage == 'profiles') ? 'class="active"' : ''; ?>>Profiles</a></li>
      <li><a href="dashboard.php" <?php echo ($currentPage == 'dashboard') ? 'class="active"' : ''; ?>>Dashboard</a></li>
      <li><a href="attendance.php" <?php echo ($currentPage == 'attendance') ? 'class="active"' : ''; ?>>Attendance</a></li>
      <li><a href="reports.php" <?php echo ($currentPage == 'reports') ? 'class="active"' : ''; ?>>Reports</a></li>
      <li><a href="assignments.php" <?php echo ($currentPage == 'assignments') ? 'class="active"' : ''; ?>>Assignments</a></li>
      <li><a href="payment.php" <?php echo ($currentPage == 'payment') ? 'class="active"' : ''; ?>>Payment</a></li>
      <li><a href="messages.php" <?php echo ($currentPage == 'messages') ? 'class="active"' : ''; ?>>Messages</a></li>
      <li><a href="login.php" <?php echo ($currentPage == 'login') ? 'class="active"' : ''; ?>>Login</a></li>
    </ul>
    <div style="display:flex;align-items:center;gap:0.75rem;">
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
    <a href="profiles.php">Profiles</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="attendance.php">Attendance</a>
    <a href="reports.php">Reports</a>
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
FILE 3: footer.php
php
<footer class="footer">
  <div class="container footer-grid">
    <div>
      <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
        <div class="logo-circle" style="font-size:1.75rem;flex-shrink:0;">🏫</div>
        <div><div style="font-size:1.125rem;font-weight:900;">Wellucation</div><div style="color:#F48FB1;font-size:0.75rem;font-weight:700;">Learn. Play. Grow</div></div>
      </div>
      <p>Nurturing young minds with love, creativity, and excellence in early childhood education.</p>
      <div class="social-links"><a href="#">f</a><a href="#">📸</a><a href="#">𝕏</a><a href="#">▶</a></div>
    </div>
    <div>
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">› Home</a></li><li><a href="about.php">› About Us</a></li>
        <li><a href="contact.php">› Contact Us</a></li><li><a href="dashboard.php">› Dashboard</a></li>
        <li><a href="enroll.php">› Enroll Now</a></li><li><a href="subscription.php">› Subscription</a></li>
        <li><a href="login.php">› Login / Register</a></li>
      </ul>
    </div>
    <div>
      <h4>Our Programs</h4>
      <ul>
        <li><a href="#">› Nursery (Ages 2–3)</a></li><li><a href="#">› Kindergarten 1 (Ages 3–4)</a></li>
        <li><a href="#">› Kindergarten 2 (Ages 4–5)</a></li><li><a href="#">› Creative Arts (All Ages)</a></li>
        <li><a href="#">› After School Care</a></li><li><a href="#">› Summer Camp</a></li>
      </ul>
    </div>
    <div>
      <h4>Get In Touch</h4>
      <ul>
        <li>📍 123 Sunshine Lane, Kidstown, CA 90210</li>
        <li>📞 +1 (555) 123-4567</li>
        <li>✉️ hello@wellucation.edu</li>
      </ul>
      <div style="margin-top:1rem;background:rgba(255,255,255,0.08);border-radius:1rem;padding:0.875rem;">
        <p style="font-size:0.75rem;margin-bottom:0.25rem;">School Hours</p>
        <p style="font-weight:700;font-size:0.875rem;">Mon – Fri: 7:00 AM – 6:00 PM</p>
        <p style="font-size:0.75rem;color:#93C5FD;">Sat: 8:00 AM – 2:00 PM</p>
      </div>
    </div>
  </div>
  <div class="footer-bottom container">
    <p>© 2026 Wellucation Nursery. All rights reserved.</p>
    <p>Made with ❤️ for little learners</p>
  </div>
</footer>
<div class="toast-container" id="toastContainer"></div>
<script src="scripts.js"></script>
</body>
</html>
FILE 4: index.php
php
<?php
session_start();
$pageTitle = "Wellucation – Learn. Play. Grow";
$currentPage = "home";
include 'header.php';
include 'navbar.php';
?>

<!-- Hero Section -->
<section class="hero-section">
  <div class="blob blob-pink" style="width:16rem;height:16rem;top:2.5rem;left:2.5rem;"></div>
  <div class="blob blob-blue" style="width:20rem;height:20rem;bottom:2.5rem;right:2.5rem;"></div>
  <div class="blob blob-yellow" style="width:24rem;height:24rem;top:50%;left:50%;transform:translate(-50%,-50%);opacity:0.1;"></div>
  <div class="hero-float bounce" style="top:5rem;right:25%;animation-duration:3s;">⭐</div>
  <div class="hero-float bounce" style="bottom:8rem;left:25%;animation-duration:4s;animation-delay:1s;">🌈</div>
  <div class="container" style="position:relative;z-index:1;width:100%;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;padding:4rem 0;" class="hero-grid">
      <div class="fade-in-up">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
          <div class="logo-circle" style="width:5rem;height:5rem;font-size:2.5rem;box-shadow:0 8px 24px rgba(0,0,0,0.12);">🏫</div>
          <div>
            <div style="color:var(--blue);font-size:1.75rem;font-weight:900;">Wellucation</div>
            <div style="color:var(--pink);font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">Learn. Play. Grow</div>
          </div>
        </div>
        <h1 style="color:var(--pink);font-size:clamp(2.5rem,5vw,3.5rem);font-weight:900;line-height:1.1;margin-bottom:1rem;"> Where Little Stars<br> <span style="color:var(--blue);">Begin to Shine! ✨</span> </h1>
        <p style="color:var(--blue);font-size:1.0625rem;line-height:1.75;margin-bottom:2rem;max-width:30rem;opacity:0.8;"> At Wellucation, we create a warm, safe, and joyful environment where children aged 2–5 discover the magic of learning through play, creativity, and friendship. </p>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
          <a href="enroll.php" class="btn btn-primary">🌟 Enroll Today →</a>
          <a href="about.php" class="btn btn-outline">▶ Our Story</a>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
          <span style="font-size:0.8125rem;font-weight:600;color:var(--blue);padding:0.375rem 1rem;border-radius:9999px;background:rgba(255,255,255,0.7);box-shadow:0 2px 6px rgba(0,0,0,0.06);">🏆 Award-Winning</span>
          <span style="font-size:0.8125rem;font-weight:600;color:var(--blue);padding:0.375rem 1rem;border-radius:9999px;background:rgba(255,255,255,0.7);box-shadow:0 2px 6px rgba(0,0,0,0.06);">🔒 Safe & Secure</span>
          <span style="font-size:0.8125rem;font-weight:600;color:var(--blue);padding:0.375rem 1rem;border-radius:9999px;background:rgba(255,255,255,0.7);box-shadow:0 2px 6px rgba(0,0,0,0.06);">❤️ EYFS Accredited</span>
        </div>
      </div>
      <div style="position:relative;max-width:30rem;margin:0 auto;width:100%;">
        <div style="position:relative;aspect-ratio:1;">
          <div style="position:absolute;top:0;right:0;width:80%;height:80%;background:linear-gradient(135deg,#FFF0F7,#EFF6FF);border-radius:1.75rem;box-shadow:0 16px 48px rgba(0,0,0,0.15);display:flex;align-items:center;justify-content:center;font-size:6rem;">👧🏼📚</div>
          <div style="position:absolute;bottom:0;left:0;width:42%;height:42%;background:linear-gradient(135deg,#FFFBEB,#FFF0F7);border-radius:1.25rem;border:4px solid white;box-shadow:0 8px 24px rgba(0,0,0,0.12);display:flex;align-items:center;justify-content:center;font-size:3rem;">👨‍👩‍👧</div>
          <div style="position:absolute;top:-1rem;left:-1rem;background:white;border-radius:1rem;box-shadow:0 8px 24px rgba(0,0,0,0.1);padding:0.75rem;display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.5rem;">🎓</span>
            <div><div style="font-size:0.6875rem;font-weight:800;color:var(--pink);">Top Rated</div><div style="color:#F59E0B;font-size:0.75rem;">★★★★★</div></div>
          </div>
          <div style="position:absolute;bottom:1rem;right:1rem;background:white;border-radius:1rem;box-shadow:0 8px 24px rgba(0,0,0,0.1);padding:0.75rem;display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.5rem;">👨‍👩‍👧</span>
            <div><div style="font-size:0.75rem;font-weight:900;color:var(--blue);">248+</div><div style="font-size:0.6875rem;color:var(--gray-500);">Happy Families</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Strip -->
<section class="stat-strip">
  <div class="container stat-strip-inner">
    <div class="stat-item"><div class="stat-icon" style="background:#FFF0F7;"><span style="font-size:1.5rem;">👥</span></div><div><div class="stat-value" style="color:var(--pink);">248+</div><div class="stat-label">Happy Children</div></div></div>
    <div class="stat-item"><div class="stat-icon" style="background:#EFF6FF;"><span style="font-size:1.5rem;">🎓</span></div><div><div class="stat-value" style="color:var(--blue);">18</div><div class="stat-label">Expert Teachers</div></div></div>
    <div class="stat-item"><div class="stat-icon" style="background:#FFFBEB;"><span style="font-size:1.5rem;">🏆</span></div><div><div class="stat-value" style="color:var(--yellow);">12</div><div class="stat-label">Award-Winning</div></div></div>
    <div class="stat-item"><div class="stat-icon" style="background:#F0FDF4;"><span style="font-size:1.5rem;">⏰</span></div><div><div class="stat-value" style="color:var(--green);">15+</div><div class="stat-label">Years of Care</div></div></div>
  </div>
</section>

<!-- Programs -->
<section class="section" style="background:var(--gray-50);">
  <div class="container">
    <div class="text-center mb-12">
      <span class="badge badge-pink mb-4" style="font-size:0.8125rem;">🎯 Our Programs</span>
      <h2 style="color:var(--pink);font-size:2.25rem;margin-bottom:0.75rem;">Designed for Every Little Learner</h2>
      <p style="color:var(--blue);font-size:1.0625rem;opacity:0.75;max-width:32rem;margin:0 auto;">Age-appropriate programs that nurture curiosity, confidence, and creativity at every stage.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;">
      <div class="program-card" style="background:#FFF0F7;border-color:#F8BBD9;"><div class="program-icon">🌱</div><span class="badge" style="background:var(--pink);color:white;margin-bottom:0.75rem;">Ages 2 – 3</span><h3 style="font-weight:900;margin-bottom:0.5rem;">Nursery Program</h3><p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;">A nurturing space where toddlers explore, play, and develop foundational social skills through guided activities.</p><a href="enroll.php" style="margin-top:1rem;font-size:0.8125rem;font-weight:700;color:var(--pink);border:none;background:none;cursor:pointer;display:flex;align-items:center;gap:0.25rem;text-decoration:none;">Learn more →</a></div>
      <div class="program-card" style="background:#EFF6FF;border-color:#BFDBFE;"><div class="program-icon">🌼</div><span class="badge" style="background:var(--blue);color:white;margin-bottom:0.75rem;">Ages 3 – 4</span><h3 style="font-weight:900;margin-bottom:0.5rem;">Kindergarten 1</h3><p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;">Building early literacy, numeracy, and creativity through hands-on learning and imaginative play.</p><a href="enroll.php" style="margin-top:1rem;font-size:0.8125rem;font-weight:700;color:var(--blue);border:none;background:none;cursor:pointer;display:flex;align-items:center;gap:0.25rem;text-decoration:none;">Learn more →</a></div>
      <div class="program-card" style="background:#FFFBEB;border-color:#FDE68A;"><div class="program-icon">🌟</div><span class="badge" style="background:var(--yellow);color:white;margin-bottom:0.75rem;">Ages 4 – 5</span><h3 style="font-weight:900;margin-bottom:0.5rem;">Kindergarten 2</h3><p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;">Preparing children for primary school with structured learning, problem-solving, and collaborative projects.</p><a href="enroll.php" style="margin-top:1rem;font-size:0.8125rem;font-weight:700;color:var(--yellow);border:none;background:none;cursor:pointer;display:flex;align-items:center;gap:0.25rem;text-decoration:none;">Learn more →</a></div>
      <div class="program-card" style="background:#F0FDF4;border-color:#BBF7D0;"><div class="program-icon">🎨</div><span class="badge" style="background:var(--green);color:white;margin-bottom:0.75rem;">All Ages</span><h3 style="font-weight:900;margin-bottom:0.5rem;">Creative Arts</h3><p style="font-size:0.875rem;color:var(--gray-600);line-height:1.6;">Unlocking imagination through painting, music, drama, and crafts that spark joy and self-expression.</p><a href="enroll.php" style="margin-top:1rem;font-size:0.8125rem;font-weight:700;color:var(--green);border:none;background:none;cursor:pointer;display:flex;align-items:center;gap:0.25rem;text-decoration:none;">Learn more →</a></div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
FILE 5: login.php
php
<?php
session_start();
$pageTitle = "Login – Wellucation Nursery";
$currentPage = "login";
include 'header.php';
include 'navbar.php';
?>

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:3rem 1.5rem;background:linear-gradient(135deg,#FFF0F7,#EFF6FF 50%,#FFFBEB);position:relative;overflow:hidden;">
  <div class="blob blob-pink" style="width:16rem;height:16rem;top:2.5rem;left:2.5rem;"></div>
  <div class="blob blob-blue" style="width:20rem;height:20rem;bottom:2.5rem;right:2.5rem;"></div>
  <div style="width:100%;max-width:28rem;position:relative;z-index:1;">
    <div class="text-center mb-6">
      <h1 style="color:var(--pink);font-size:2.25rem;font-weight:900;margin-bottom:0.5rem;">Welcome to Wellucation</h1>
      <p style="color:var(--blue);opacity:0.75;">Sign in to access your account or create a new one</p>
    </div>
    <div style="background:white;border-radius:1.75rem;box-shadow:0 20px 48px rgba(0,0,0,0.12);padding:2rem;">
      <div style="display:grid;grid-template-columns:1fr 1fr;background:var(--gray-100);border-radius:0.875rem;padding:0.25rem;margin-bottom:2rem;" id="loginTabs">
        <button id="loginTabBtn" onclick="switchLoginTab('login')" style="padding:0.625rem;border-radius:0.75rem;border:none;cursor:pointer;font-weight:700;font-size:0.875rem;background:white;color:var(--blue);box-shadow:0 1px 4px rgba(0,0,0,0.1);">🔑 Login</button>
        <button id="registerTabBtn" onclick="switchLoginTab('register')" style="padding:0.625rem;border-radius:0.75rem;border:none;cursor:pointer;font-weight:700;font-size:0.875rem;background:transparent;color:var(--gray-500);">➕ Register</button>
      </div>
      <form id="loginForm" method="POST" action="authenticate.php">
        <div class="form-group"><label class="form-label">Email Address</label><input type="email" name="email" required class="form-input" placeholder="your.email@example.com"></div>
        <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" required class="form-input" placeholder="••••••••"></div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;font-size:0.875rem;">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;color:var(--blue);"><input type="checkbox" name="remember"> Remember me</label>
          <a href="forgot-password.php" style="color:var(--pink);font-weight:600;">Forgot password?</a>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:1rem;">Sign In →</button>
      </form>
      <form id="registerForm" method="POST" action="register_user.php" style="display:none;">
        <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="fullname" required class="form-input" placeholder="John Doe"></div>
        <div class="form-group"><label class="form-label">Email Address</label><input type="email" name="email" required class="form-input" placeholder="your.email@example.com"></div>
        <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" required class="form-input" placeholder="••••••••"></div>
        <div class="form-group"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" required class="form-input" placeholder="••••••••"></div>
        <div style="margin-bottom:1.5rem;font-size:0.875rem;"><label style="display:flex;align-items:flex-start;gap:0.5rem;cursor:pointer;color:var(--blue);"><input type="checkbox" name="terms" required style="margin-top:2px;"> I agree to the <a href="#" style="color:var(--pink);">Terms & Conditions</a> and <a href="#" style="color:var(--pink);">Privacy Policy</a></label></div>
        <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:center;padding:1rem;">Create Account →</button>
      </form>
      <p style="text-align:center;margin-top:1.5rem;font-size:0.875rem;color:var(--gray-500);">Need help? <a href="contact.php" style="color:var(--pink);font-weight:600;">Contact Support</a></p>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
FILE 6: authenticate.php (Backend Handler)
php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

// In production, fetch from database
// For demo purposes, hardcoded credentials
$valid_users = [
    'admin@wellucation.edu' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
    'teacher@wellucation.edu' => ['password' => 'teacher123', 'role' => 'teacher', 'name' => 'Teacher User'],
    'parent@wellucation.edu' => ['password' => 'parent123', 'role' => 'parent', 'name' => 'Parent User']
];

if (isset($valid_users[$email]) && $valid_users[$email]['password'] === $password) {
    $_SESSION['user_id'] = $email;
    $_SESSION['role'] = $valid_users[$email]['role'];
    $_SESSION['name'] = $valid_users[$email]['name'];
    $_SESSION['message'] = 'Login successful! Welcome back.';
    
    // Redirect based on role
    if ($_SESSION['role'] == 'admin') {
        header('Location: dashboard.php');
    } elseif ($_SESSION['role'] == 'teacher') {
        header('Location: attendance.php');
    } else {
        header('Location: profiles.php');
    }
    exit;
} else {
    $_SESSION['error'] = 'Invalid email or password';
    header('Location: login.php');
    exit;
}
?>
FILE 7: register_user.php (Backend Handler)
php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$fullname = htmlspecialchars($_POST['fullname'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (!$fullname || !$email || !$password) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: login.php');
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match';
    header('Location: login.php');
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['error'] = 'Password must be at least 6 characters';
    header('Location: login.php');
    exit;
}

// In production, insert into database
// $hashed = password_hash($password, PASSWORD_DEFAULT);
// $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password_hash, role) VALUES (?, ?, ?, 'parent')");

$_SESSION['message'] = 'Account created successfully! Please login.';
header('Location: login.php');
exit;
?>
FILE 8: logout.php
php
<?php
session_start();
session_destroy();
header('Location: login.php');
exit;
?>
FILE 9: attendance.php
php
<?php
session_start();
$pageTitle = "Attendance – Wellucation Nursery";
$currentPage = "attendance";

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';

// Mock students data (in production, fetch from database)
$students = [
    ['id' => 1, 'name' => 'Emma Johnson', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 2, 'name' => 'Noah Williams', 'emoji' => '👦', 'class' => 'KG1'],
    ['id' => 3, 'name' => 'Sophia Brown', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 4, 'name' => 'Liam Davis', 'emoji' => '👦', 'class' => 'KG1'],
    ['id' => 5, 'name' => 'Olivia Miller', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 6, 'name' => 'Mason Wilson', 'emoji' => '👦', 'class' => 'KG1'],
    ['id' => 7, 'name' => 'Ava Chen', 'emoji' => '👧', 'class' => 'KG1'],
    ['id' => 8, 'name' => 'James Park', 'emoji' => '👦', 'class' => 'KG1'],
];
?>

<section style="padding:3rem 0;background:linear-gradient(135deg,#EFF6FF,#FFF0F7);position:relative;overflow:hidden;">
  <div style="position:absolute;top:1.5rem;right:2.5rem;font-size:2.5rem;opacity:0.2;">📋</div>
  <div class="container-sm text-center">
    <span class="badge badge-blue mb-4">📋 Attendance Management</span>
    <h1 style="color:var(--pink);font-size:2.5rem;font-weight:900;margin-bottom:0.5rem;">Attendance Tracking System</h1>
    <p style="color:var(--blue);opacity:0.8;">Manage and monitor student attendance with real-time tracking, analytics, and smart reports.</p>
  </div>
</section>

<div class="view-tabs">
  <div class="view-tabs-inner">
    <button class="view-tab-btn" onclick="setAttView('teacher')" style="background:var(--blue);color:white;">👩‍🏫 Teacher View</button>
    <button class="view-tab-btn" onclick="setAttView('parent')" style="background:#F0FDF4;color:var(--green);">👨‍👩‍👧 Parent View</button>
    <button class="view-tab-btn" onclick="setAttView('admin')" style="background:#FFF0F7;color:var(--pink);">🛡️ Admin View</button>
  </div>
</div>

<section style="padding:2rem 0;background:var(--gray-50);">
  <div class="container">
    <!-- TEACHER VIEW -->
    <div id="att-view-teacher">
      <form method="POST" action="submit_attendance.php" id="attendanceForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
        
        <div style="background:white;border-radius:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);padding:1.25rem;margin-bottom:1.25rem;">
          <h3 style="color:var(--blue);font-weight:900;font-size:1.125rem;margin-bottom:1rem;">👩‍🏫 Mark Attendance</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;" class="hero-grid">
            <div><label class="form-label" style="color:var(--blue);">Select Class</label><select class="form-select" name="class"><option>🌻 KG1 – Sunflower</option><option>🌈 KG2 – Rainbow</option><option>🦋 Nursery – Butterfly</option><option>⭐ KG2 – Stars</option></select></div>
            <div><label class="form-label" style="color:var(--blue);">Date</label><input type="date" name="date_display" class="form-input" value="<?php echo date('Y-m-d'); ?>"></div>
            <div style="display:flex;flex-direction:column;justify-content:flex-end;"><button type="button" onclick="markAllPresent()" style="background:var(--green);color:white;border:none;padding:0.75rem;border-radius:0.875rem;font-weight:700;cursor:pointer;font-size:0.875rem;">✅ Mark All Present</button></div>
          </div>
        </div>
        
        <div style="background:white;border-radius:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:hidden;">
          <div style="padding:1rem;border-bottom:1px solid var(--gray-100);background:#EFF6FF;display:flex;align-items:center;justify-content:space-between;">
            <h4 style="font-weight:900;color:var(--blue);">Students – KG1 Sunflower</h4>
            <span style="font-size:0.75rem;color:var(--gray-400);" id="markedCount">0/<?php echo count($students); ?> marked</span>
          </div>
          <div id="studentRows">
            <?php foreach ($students as $index => $student): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1rem;border-top:1px solid #F9FAFB;flex-wrap:wrap;gap:0.5rem;">
              <div style="display:flex;align-items:center;gap:0.75rem;"><span style="font-size:1.5rem;"><?php echo $student['emoji']; ?></span><div><div style="font-weight:700;font-size:0.875rem;"><?php echo $student['name']; ?></div><div style="font-size:0.75rem;color:var(--gray-400);"><?php echo $student['class']; ?></div></div></div>
              <div style="display:flex;gap:0.5rem;">
                <button type="button" onclick="setStatus(<?php echo $index; ?>, 'present')" data-student="<?php echo $student['id']; ?>" class="status-btn status-present" style="padding:0.375rem 0.75rem;border-radius:0.625rem;border:none;cursor:pointer;font-size:0.8125rem;font-weight:700;background:#F0FDF4;color:var(--green);">✅</button>
                <button type="button" onclick="setStatus(<?php echo $index; ?>, 'absent')" class="status-btn status-absent" style="padding:0.375rem 0.75rem;border-radius:0.625rem;border:none;cursor:pointer;font-size:0.8125rem;font-weight:700;background:#FEF2F2;color:#EF4444;">❌</button>
                <button type="button" onclick="setStatus(<?php echo $index; ?>, 'late')" class="status-btn status-late" style="padding:0.375rem 0.75rem;border-radius:0.625rem;border:none;cursor:pointer;font-size:0.8125rem;font-weight:700;background:#FFFBEB;color:var(--yellow);">⏰</button>
              </div>
              <input type="hidden" name="attendance[<?php echo $student['id']; ?>]" id="status_<?php echo $index; ?>" value="">
            </div>
            <?php endforeach; ?>
          </div>
          <div style="padding:1rem;border-top:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.75rem;color:var(--gray-400);" id="markedCount2">0/<?php echo count($students); ?> marked</span>
            <button type="submit" id="submitAttBtn" style="background:linear-gradient(135deg,var(--blue),var(--blue-dark));color:white;border:none;padding:0.625rem 1.5rem;border-radius:0.875rem;font-weight:700;cursor:pointer;font-size:0.875rem;opacity:0.5;cursor:not-allowed;" disabled>✅ Submit Attendance</button>
          </div>
        </div>
      </form>
    </div>
    
    <!-- PARENT VIEW (simplified) -->
    <div id="att-view-parent" style="display:none;">
      <div style="background:white;border-radius:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);padding:1.25rem;">
        <h3 style="font-weight:900;color:var(--blue);">Parent View - Student Attendance</h3>
        <p>View your child's attendance records here.</p>
      </div>
    </div>
    
    <!-- ADMIN VIEW (simplified) -->
    <div id="att-view-admin" style="display:none;">
      <div style="background:white;border-radius:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.05);padding:1.25rem;">
        <h3 style="font-weight:900;color:var(--pink);">Admin View - School-wide Reports</h3>
        <p>View school-wide attendance analytics.</p>
      </div>
    </div>
  </div>
</section>

<script>
let studentStatus = new Array(<?php echo count($students); ?>).fill(null);

function updateAttStats() {
    const present = studentStatus.filter(s => s === 'present').length;
    const absent = studentStatus.filter(s => s === 'absent').length;
    const late = studentStatus.filter(s => s === 'late').length;
    const marked = present + absent + late;
    const total = <?php echo count($students); ?>;
    
    document.getElementById('markedCount').innerText = marked + '/' + total + ' marked';
    document.getElementById('markedCount2').innerText = marked + '/' + total + ' marked';
    
    const submitBtn = document.getElementById('submitAttBtn');
    if (submitBtn) {
        const allMarked = marked === total;
        submitBtn.disabled = !allMarked;
        submitBtn.style.opacity = allMarked ? '1' : '0.5';
        submitBtn.style.cursor = allMarked ? 'pointer' : 'not-allowed';
    }
}

function setStatus(index, status) {
    studentStatus[index] = status;
    const statusInput = document.getElementById('status_' + index);
    if (statusInput) statusInput.value = status;
    
    // Update button styles
    const row = document.querySelector(`#studentRows > div:nth-child(${index + 1})`);
    if (row) {
        const btns = row.querySelectorAll('.status-btn');
        btns.forEach(btn => {
            btn.style.opacity = '0.7';
        });
        const activeBtn = row.querySelector(`.status-${status}`);
        if (activeBtn) activeBtn.style.opacity = '1';
    }
    updateAttStats();
}

function markAllPresent() {
    for (let i = 0; i < studentStatus.length; i++) {
        studentStatus[i] = 'present';
        const statusInput = document.getElementById('status_' + i);
        if (statusInput) statusInput.value = 'present';
    }
    updateAttStats();
    location.reload();
}

function setAttView(view) {
    const teacherView = document.getElementById('att-view-teacher');
    const parentView = document.getElementById('att-view-parent');
    const adminView = document.getElementById('att-view-admin');
    
    teacherView.style.display = view === 'teacher' ? '' : 'none';
    parentView.style.display = view === 'parent' ? '' : 'none';
    adminView.style.display = view === 'admin' ? '' : 'none';
}

updateAttStats();
</script>

<?php include 'footer.php'; ?>
FILE 10: submit_attendance.php (Backend Handler)
php
<?php
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    http_response_code(403);
    exit('Forbidden - Teacher access required');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: attendance.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF validation failed');
}

$date = $_POST['date'] ?? date('Y-m-d');
$attendance = $_POST['attendance'] ?? [];

if (empty($attendance)) {
    $_SESSION['error'] = 'No attendance data submitted';
    header('Location: attendance.php');
    exit;
}

// In production, save to database
// foreach ($attendance as $student_id => $status) {
//     $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status, marked_by) VALUES (?, ?, ?, ?)");
//     $stmt->execute([$student_id, $date, $status, $_SESSION['user_id']]);
// }

// Log the activity
error_log("Teacher {$_SESSION['user_id']} submitted attendance for " . date('Y-m-d'));

$_SESSION['message'] = 'Attendance submitted successfully for ' . date('F j, Y');
header('Location: attendance.php');
exit;
?>
FILE 11: contact.php
php
<?php
session_start();
$pageTitle = "Contact Us – Wellucation Nursery";
$currentPage = "contact";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';
?>

<section style="padding:4rem 0;background:linear-gradient(135deg,#FFF0F7,#EFF6FF);position:relative;overflow:hidden;">
  <div style="position:absolute;top:2rem;right:2.5rem;font-size:2.5rem;opacity:0.2;">📬</div>
  <div class="container-sm text-center">
    <span class="badge" style="background:white;color:var(--pink);margin-bottom:1rem;">📬 Get In Touch</span>
    <h1 style="color:var(--pink);font-size:3rem;font-weight:900;margin-bottom:1rem;">We'd Love to Hear from You!</h1>
    <p style="color:var(--blue);font-size:1.125rem;line-height:1.75;opacity:0.8;">Have questions about enrollment, programs, or our facilities? Our friendly team is here to help every step of the way.</p>
  </div>
</section>

<section class="section" style="background:var(--gray-50);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;" class="hero-grid">
      <div>
        <h2 style="color:var(--pink);font-size:1.875rem;font-weight:900;margin-bottom:0.5rem;">Send Us a Message</h2>
        <p style="color:var(--blue);font-size:0.875rem;margin-bottom:1.5rem;opacity:0.75;">Fill in the form and our team will get back to you within 24 hours.</p>
        <?php if (isset($_SESSION['message'])): ?>
        <div style="background:#F0FDF4;border:2px solid #BBF7D0;border-radius:1rem;padding:1rem;margin-bottom:1.5rem;">
          <div style="color:#166534;font-weight:900;">✅ <?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
        <div style="background:#FEF2F2;border:2px solid #FECACA;border-radius:1rem;padding:1rem;margin-bottom:1.5rem;">
          <div style="color:#991B1B;font-weight:900;">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        </div>
        <?php endif; ?>
        <form method="POST" action="contact_submit.php" style="background:white;border-radius:1.75rem;box-shadow:0 4px 16px rgba(0,0,0,0.07);padding:1.5rem;display:flex;flex-direction:column;gap:1rem;">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div><label class="form-label">Full Name *</label><input type="text" name="name" required class="form-input" placeholder="Your full name"></div>
            <div><label class="form-label">Email Address *</label><input type="email" name="email" required class="form-input" placeholder="your@email.com"></div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div><label class="form-label">Phone Number</label><input type="tel" name="phone" class="form-input" placeholder="+1 (555) 000-0000"></div>
            <div><label class="form-label">I am a...</label><select name="user_type" class="form-select"><option>Parent / Guardian</option><option>Teacher Applicant</option><option>Administrator</option><option>Other</option></select></div>
          </div>
          <div><label class="form-label">Subject *</label><input type="text" name="subject" required class="form-input" placeholder="e.g., Enrollment inquiry, School tour..."></div>
          <div><label class="form-label">Message *</label><textarea name="message" required class="form-textarea" placeholder="Tell us how we can help you..."></textarea></div>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">✉️ Send Message</button>
        </form>
      </div>
      <div>
        <div class="map-box" style="background:#EFF6FF;border-radius:1.75rem;padding:2rem;text-align:center;">
          <div style="font-size:3rem;margin-bottom:1rem;">📍</div>
          <h3 style="font-weight:900;color:var(--blue);">Wellucation Nursery</h3>
          <p style="color:var(--gray-600);">123 Sunshine Lane, Kidstown, CA 90210</p>
        </div>
        <div style="background:white;border-radius:1.75rem;padding:1.5rem;margin-top:1rem;">
          <h3 style="font-weight:900;margin-bottom:1rem;">School Hours</h3>
          <p><strong>Monday – Friday:</strong> 7:00 AM – 6:00 PM</p>
          <p><strong>Saturday:</strong> 8:00 AM – 2:00 PM</p>
          <p><strong>Sunday:</strong> Closed</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
FILE 12: contact_submit.php (Backend Handler)
php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF validation failed');
}

// Sanitize inputs
$name = htmlspecialchars($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars($_POST['phone'] ?? '');
$user_type = htmlspecialchars($_POST['user_type'] ?? '');
$subject = htmlspecialchars($_POST['subject'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Validation
if (!$name || !$email || !$subject || !$message) {
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: contact.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: contact.php');
    exit;
}

// In production, send email or save to database
$to = "hello@wellucation.edu";
$email_subject = "Contact Form: $subject";
$email_body = "Name: $name\nEmail: $email\nPhone: $phone\nUser Type: $user_type\n\nMessage:\n$message";
$headers = "From: $email";

// mail($to, $email_subject, $email_body, $headers);

// Save to database (mock)
// $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, user_type, subject, message) VALUES (?, ?, ?, ?, ?, ?)");

$_SESSION['message'] = 'Thank you for your message! We will reply within 24 hours.';
header('Location: contact.php');
exit;
?>
FILE 13: enroll.php
php
<?php
session_start();
$pageTitle = "Enroll Now – Wellucation Nursery";
$currentPage = "enroll";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';
?>

<section style="padding:4rem 0;background:linear-gradient(135deg,#FFF0F7,#EFF6FF);position:relative;overflow:hidden;">
  <div class="container-sm text-center">
    <span class="badge" style="background:white;color:var(--pink);margin-bottom:1rem;">🌟 Enrollment</span>
    <h1 style="color:var(--pink);font-size:3rem;font-weight:900;margin-bottom:1rem;">Join the Wellucation Family</h1>
    <p style="color:var(--blue);font-size:1.0625rem;opacity:0.75;">Start your child's journey towards a bright future. Fill out the form below to apply for enrollment.</p>
  </div>
</section>

<section class="section" style="background:var(--gray-50);">
  <div class="container-sm">
    <?php if (isset($_SESSION['message'])): ?>
    <div style="background:#F0FDF4;border:2px solid #BBF7D0;border-radius:1rem;padding:1rem;margin-bottom:1.5rem;">
      <div style="color:#166534;font-weight:900;">✅ <?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    </div>
    <?php endif; ?>
    
    <div style="background:white;border-radius:1.75rem;box-shadow:0 8px 24px rgba(0,0,0,0.07);padding:2rem;">
      <form method="POST" action="process_enrollment.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <div style="margin-bottom:2.5rem;">
          <h3 style="color:var(--pink);font-size:1.375rem;font-weight:900;margin-bottom:1.5rem;">👤 Parent/Guardian Information</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;" class="hero-grid">
            <div><label class="form-label">Full Name *</label><input type="text" name="parent_name" required class="form-input" placeholder="John Doe"></div>
            <div><label class="form-label">Email Address *</label><input type="email" name="parent_email" required class="form-input" placeholder="john@example.com"></div>
            <div><label class="form-label">Phone Number *</label><input type="tel" name="parent_phone" required class="form-input" placeholder="+1 (555) 123-4567"></div>
            <div><label class="form-label">Home Address *</label><input type="text" name="address" required class="form-input" placeholder="123 Main St, City, State"></div>
          </div>
        </div>
        
        <div style="margin-bottom:2.5rem;">
          <h3 style="color:var(--pink);font-size:1.375rem;font-weight:900;margin-bottom:1.5rem;">👶 Child Information</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;" class="hero-grid">
            <div><label class="form-label">Child's Full Name *</label><input type="text" name="child_name" required class="form-input" placeholder="Emma Doe"></div>
            <div><label class="form-label">Date of Birth *</label><input type="date" name="child_dob" required class="form-input"></div>
            <div><label class="form-label">Select Program *</label><select name="program" required class="form-select"><option value="">Choose a program</option><option>Nursery (Ages 2-3)</option><option>Kindergarten 1 (Ages 3-4)</option><option>Kindergarten 2 (Ages 4-5)</option></select></div>
            <div><label class="form-label">Preferred Start Date *</label><input type="date" name="start_date" required class="form-input"></div>
          </div>
        </div>
        
        <div style="margin-bottom:2.5rem;">
          <h3 style="color:var(--pink);font-size:1.375rem;font-weight:900;margin-bottom:1.5rem;">📞 Emergency Contact</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;" class="hero-grid">
            <div><label class="form-label">Emergency Contact Name *</label><input type="text" name="emergency_name" required class="form-input" placeholder="Jane Doe"></div>
            <div><label class="form-label">Emergency Phone *</label><input type="tel" name="emergency_phone" required class="form-input" placeholder="+1 (555) 987-6543"></div>
          </div>
        </div>
        
        <div style="margin-bottom:2.5rem;">
          <h3 style="color:var(--pink);font-size:1.375rem;font-weight:900;margin-bottom:1.5rem;">📋 Additional Information</h3>
          <div class="form-group"><label class="form-label">Medical Information / Allergies</label><textarea name="medical_info" class="form-textarea" placeholder="Please list any allergies, medical conditions, or special needs..."></textarea></div>
          <div class="form-group"><label class="form-label">Additional Comments or Questions</label><textarea name="comments" class="form-textarea" placeholder="Tell us anything else you'd like us to know..."></textarea></div>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:1.125rem;font-size:1rem;">Submit Enrollment Application →</button>
      </form>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
FILE 14: process_enrollment.php (Backend Handler)
php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: enroll.php');
    exit;
}

// CSRF validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF validation failed');
}

// Sanitize inputs
$parent_name = htmlspecialchars($_POST['parent_name'] ?? '');
$parent_email = filter_var($_POST['parent_email'] ?? '', FILTER_SANITIZE_EMAIL);
$parent_phone = htmlspecialchars($_POST['parent_phone'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$child_name = htmlspecialchars($_POST['child_name'] ?? '');
$child_dob = $_POST['child_dob'] ?? '';
$program = htmlspecialchars($_POST['program'] ?? '');
$start_date = $_POST['start_date'] ?? '';
$emergency_name = htmlspecialchars($_POST['emergency_name'] ?? '');
$emergency_phone = htmlspecialchars($_POST['emergency_phone'] ?? '');
$medical_info = htmlspecialchars($_POST['medical_info'] ?? '');
$comments = htmlspecialchars($_POST['comments'] ?? '');

// Validation
$required = [$parent_name, $parent_email, $parent_phone, $address, $child_name, $child_dob, $program, $start_date, $emergency_name, $emergency_phone];
foreach ($required as $field) {
    if (empty($field)) {
        $_SESSION['error'] = 'Please fill in all required fields';
        header('Location: enroll.php');
        exit;
    }
}

if (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address';
    header('Location: enroll.php');
    exit;
}

// Generate application ID
$app_id = 'APP-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

// In production, save to database
// $stmt = $pdo->prepare("INSERT INTO enrollments (...) VALUES (...)");

// Send confirmation email (mock)
$to = $parent_email;
$subject = "Enrollment Application Received - Wellucation Nursery";
$message = "Dear $parent_name,\n\nThank you for submitting an enrollment application for $child_name. Your application ID is $app_id.\n\nWe will review your application and contact you within 48 hours.\n\nBest regards,\nWellucation Nursery Team";
// mail($to, $subject, $message);

$_SESSION['message'] = "Enrollment application submitted successfully! Your application ID is $app_id. We'll contact you within 48 hours.";
header('Location: enroll.php');
exit;
?>
FILE 15: dashboard.php
php
<?php
session_start();
$pageTitle = "Dashboard – Wellucation Nursery";
$currentPage = "dashboard";

// Check if user is logged in (for demo, allow access)
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }

include 'header.php';
include 'navbar.php';
?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div style="padding:0.75rem;border-bottom:1px solid var(--gray-100);">
      <span style="font-size:0.6875rem;font-weight:900;color:var(--gray-400);text-transform:uppercase;">Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
      <button class="sidebar-nav-btn active" onclick="setDashNav(this,'overview')">🏠 <span>Overview</span></button>
      <button class="sidebar-nav-btn" onclick="setDashNav(this,'students')">👥 <span>Students</span></button>
      <button class="sidebar-nav-btn" onclick="setDashNav(this,'teachers')">🎓 <span>Teachers</span></button>
      <button class="sidebar-nav-btn" onclick="setDashNav(this,'attendance')">📅 <span>Attendance</span></button>
      <button class="sidebar-nav-btn" onclick="setDashNav(this,'analytics')">📊 <span>Analytics</span></button>
    </nav>
    <div style="margin:0.75rem;border-radius:1rem;padding:0.875rem;text-align:center;background:linear-gradient(135deg,var(--pink),var(--pink-dark));">
      <div style="color:white;font-size:0.75rem;font-weight:700;margin-bottom:0.5rem;">Quick Action</div>
      <a href="add_user.php" style="color:white;font-size:0.75rem;font-weight:600;background:rgba(255,255,255,0.2);border:none;padding:0.5rem 1rem;border-radius:0.625rem;cursor:pointer;display:inline-block;text-decoration:none;">+ Add Student</a>
    </div>
  </aside>
  
  <main class="dashboard-main">
    <div class="dashboard-topbar">
      <div>
        <div style="font-size:0.75rem;color:var(--gray-400);">Overview</div>
        <h2 style="color:var(--blue);font-weight:900;font-size:1.125rem;">Admin Dashboard</h2>
      </div>
      <div style="display:flex;align-items:center;gap:0.75rem;">
        <div style="width:2rem;height:2rem;border-radius:0.875rem;background:var(--pink);display:flex;align-items:center;justify-content:center;color:white;font-size:0.875rem;font-weight:900;">A</div>
      </div>
    </div>
    
    <div style="padding:1.5rem;">
      <div style="border-radius:1.75rem;padding:1.25rem;margin-bottom:1.5rem;background:linear-gradient(135deg,var(--blue),var(--pink));">
        <div style="color:white;font-size:1.25rem;font-weight:900;">Welcome to Wellucation Dashboard! 👋</div>
        <div style="color:rgba(255,255,255,0.8);font-size:0.875rem;margin-top:0.25rem;">Manage students, attendance, and school operations from one place.</div>
      </div>
      
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;" class="dash-grid">
        <div class="overview-card"><div><div style="font-size:1.5rem;font-weight:900;color:var(--blue);">248</div><div style="font-size:0.75rem;font-weight:600;color:var(--gray-600);">Total Students</div></div></div>
        <div class="overview-card"><div><div style="font-size:1.5rem;font-weight:900;color:var(--pink);">18</div><div style="font-size:0.75rem;font-weight:600;color:var(--gray-600);">Total Teachers</div></div></div>
        <div class="overview-card"><div><div style="font-size:1.5rem;font-weight:900;color:var(--green);">94%</div><div style="font-size:0.75rem;font-weight:600;color:var(--gray-600);">Attendance Rate</div></div></div>
      </div>
      
      <div style="background:white;border-radius:1.5rem;padding:1.25rem;">
        <h3 style="font-weight:900;margin-bottom:1rem;">Recent Students</h3>
        <table class="data-table" style="width:100%">
          <thead><tr><th>Student</th><th>Class</th><th>Status</th></tr></thead>
          <tbody>
            <tr><td>Emma Johnson</td><td>KG1 – Sunflower</td><td><span style="color:var(--green);">✅ Active</span></td></tr>
            <tr><td>Noah Williams</td><td>KG2 – Rainbow</td><td><span style="color:var(--green);">✅ Active</span></td></tr>
            <tr><td>Sophia Brown</td><td>Nursery – Butterfly</td><td><span style="color:#EF4444;">⚠️ Concern</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<script>
function setDashNav(btn, section) {
    document.querySelectorAll('.sidebar-nav-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const topbar = document.querySelector('.dashboard-topbar h2');
    if (topbar) topbar.textContent = section.charAt(0).toUpperCase() + section.slice(1);
}
</script>

<?php include 'footer.php'; ?>
FILE 16: add_user.php (Backend Handler)
php
<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden - Admin access required');
}

// Display form for GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Add User</title><link rel="stylesheet" href="styles.css"></head>
    <body style="padding:2rem;max-width:600px;margin:0 auto;">
        <h1 style="color:var(--pink);">Add New User</h1>
        <form method="POST" action="add_user.php">
            <div class="form-group"><label>Full Name</label><input type="text" name="name" class="form-input" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-input" required></div>
            <div class="form-group"><label>Role</label><select name="role" class="form-select"><option>student</option><option>teacher</option><option>parent</option></select></div>
            <div class="form-group"><label>Class (for students)</label><input type="text" name="class" class="form-input"></div>
            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="dashboard.php" class="btn">Cancel</a>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $role = htmlspecialchars($_POST['role'] ?? '');
    $class = htmlspecialchars($_POST['class'] ?? '');
    
    if (!$name || !$email || !$role) {
        $_SESSION['error'] = 'Please fill in all required fields';
        header('Location: add_user.php');
        exit;
    }
    
    // In production, insert into database
    // $stmt = $pdo->prepare("INSERT INTO users (name, email, role, class) VALUES (?, ?, ?, ?)");
    
    $_SESSION['message'] = "User $name added successfully!";
    header('Location: dashboard.php');
    exit;
}
?>
FILE 17: profiles.php
php
<?php
session_start();
$pageTitle = "Profiles – Wellucation Nursery";
$currentPage = "profiles";
include 'header.php';
include 'navbar.php';
?>

<section style="padding:3rem 0;background:linear-gradient(135deg,#FFF0F7,#EFF6FF);position:relative;overflow:hidden;">
  <div class="container-sm text-center">
    <span class