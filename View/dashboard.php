<?php
session_start();
$pageTitle = "Dashboard – Wellucation Nursery";
$currentPage = "dashboard";
$pageCss = 'Dashboard.css';

$panel = isset($_GET['panel']) ? $_GET['panel'] : 'overview';

// Check if user is logged in (for demo, allow access)
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }

include 'header.php';
include 'navbar.php';

// Import database models
require_once '../Models/Database.php';

// Initialize variables
$totalStudents = 0;
$totalTeachers = 0;
$attendanceRate = 0;
$recentStudents = [];

try {
    $db = Database::getInstance();
    
    // Count total students
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM Child");
    $totalStudents = $result['count'] ?? 0;
    
    // Count total teachers
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM Teacher");
    $totalTeachers = $result['count'] ?? 0;
    
    // Calculate attendance rate
    $result = $db->fetchOne("
        SELECT 
            ROUND(
                (COUNT(CASE WHEN status = 'Present' THEN 1 END) / COUNT(*)) * 100
            ) as rate
        FROM Attendance
        WHERE sessionDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ");
    $attendanceRate = $result['rate'] ?? 94;
    
    // Get recent students
    $recentStudents = $db->fetchAll("
        SELECT c.childID, c.name, c.gender, e.status as enrollmentStatus
        FROM Child c
        LEFT JOIN Enrollment e ON c.childID = e.childID
        ORDER BY c.childID DESC
        LIMIT 3
    ");
    
} catch (Exception $e) {
    // Use default values if database fails
    $totalStudents = 248;
    $totalTeachers = 18;
    $attendanceRate = 94;
    $recentStudents = [
        ['childID' => 1, 'name' => 'Emma Johnson', 'gender' => 'Female', 'enrollmentStatus' => 'Active'],
        ['childID' => 2, 'name' => 'Noah Williams', 'gender' => 'Male', 'enrollmentStatus' => 'Active'],
        ['childID' => 3, 'name' => 'Sophia Brown', 'gender' => 'Female', 'enrollmentStatus' => 'Active'],
    ];
}
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($pageTitle); ?></title>
	<link rel="stylesheet" href="css/home.css" />
	<link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
<!-- ══════════════ TOP BAR ══════════════ -->
<div class="topbar" id="topbar">
  <div class="container topbar__inner">
    <div class="topbar__left">
      <span class="topbar__item">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"/>
        </svg>
        +1 (555) 123-4567
      </span>
      <span class="topbar__item">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect width="20" height="16" x="2" y="4" rx="2" />
          <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
        </svg>
        hello@wellucation.edu
      </span>
    </div>
    <div class="topbar__right">
      <span>Follow us:</span>
      <a href="#" class="topbar__social" aria-label="Facebook">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
        </svg>
      </a>
      <a href="#" class="topbar__social" aria-label="Instagram">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
          <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
          <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
        </svg>
      </a>
      <a href="#" class="topbar__social" aria-label="Twitter">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>
        </svg>
      </a>
      <a href="#" class="topbar__social" aria-label="YouTube">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/>
          <polygon points="10 15 15 12 10 9 10 15" />
        </svg>
      </a>
    </div>
  </div>
</div>

<!-- ══════════════ NAVBAR ══════════════ -->
<nav class="navbar" id="navbar">
  <div class="container navbar__inner">
    <a href="Index.php" class="navbar__logo">
      <div class="navbar__logo-img">
        <svg style="font-size:1.5rem">🌟</svg>
      </div>
      <div class="navbar__logo-text">
        <span class="navbar__logo-name">Wellucation</span>
        <span class="navbar__logo-tagline">Learn. Play. Grow</span>
      </div>
    </a>

    <div class="navbar__links" id="navLinks">
      <a href="Index.php" class="nav-link">Home</a>
      <a href="about.php" class="nav-link">About Us</a>
      <a href="contact.php" class="nav-link">Contact Us</a>
      <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
        <a href="logout.php" class="nav-link">Logout</a>
      <?php else: ?>
        <a href="login.php" class="nav-link">Login</a>
      <?php endif; ?>
    </div>

    <div class="navbar__right">
      <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
        <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="4" x2="20" y1="12" y2="12" />
          <line x1="4" x2="20" y1="6" y2="6" />
          <line x1="4" x2="20" y1="18" y2="18" />
        </svg>
      </button>
    </div>
  </div>
</nav>

<!-- ══════════════ DASHBOARD LAYOUT ══════════════ -->
<div class="dashboard-wrapper">
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar__header">
      <span class="sidebar__label" id="sidebarLabel">Admin Panel</span>
      <button class="sidebar__toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg>
      </button>
    </div>

    <nav class="sidebar__nav" id="sidebarNav">
      <a href="dashboard.php?panel=overview" class="sidebar__item <?php echo $panel === 'overview' ? 'active' : ''; ?>" data-section="overview">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
          <polyline points="9 22 9 12 15 12 15 22" />
        </svg>
        <span class="sidebar__item-label">Overview</span>
      </a>
  </aside>

  <!-- MAIN -->
  <main class="dash-main">
    <!-- Topbar -->
    <div class="dash-topbar">
      <div class="dash-topbar__left">
        <div class="dash-topbar__breadcrumb" id="breadcrumb"><?php echo ucfirst($panel); ?></div>
        <h2 class="dash-topbar__title">Admin Dashboard</h2>
      </div>
      <div class="dash-topbar__right">
        <div class="dash-search-wrap">
          <svg class="dash-search-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
          </svg>
          <input class="dash-search" type="text" placeholder="Search..." />
        </div>
        <button class="dash-bell-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
          </svg>
          <span class="dash-bell-dot"></span>
        </button>
        <div class="dash-avatar">S</div>
      </div>
    </div>
    
    <div class="dashboard-main-inner">
      <?php if ($panel === 'overview' || $panel === 'students' || $panel === 'teachers' || $panel === 'analytics'): ?>
      <div class="dashboard-welcome">
        <div class="dashboard-welcome-title">Welcome to Wellucation Dashboard! 👋</div>
        <div class="dashboard-welcome-copy">Manage students, attendance, and school operations from one place.</div>
      </div>
      
      <div class="dashboard-stats-grid">
        <div class="overview-card overview-card--metric"><div><div class="metric-value metric-value--blue"><?php echo htmlspecialchars($totalStudents); ?></div><div class="metric-label">Total Students</div></div></div>
        <div class="overview-card overview-card--metric"><div><div class="metric-value metric-value--pink"><?php echo htmlspecialchars($totalTeachers); ?></div><div class="metric-label">Total Teachers</div></div></div>
        <div class="overview-card overview-card--metric"><div><div class="metric-value metric-value--green"><?php echo htmlspecialchars($attendanceRate); ?>%</div><div class="metric-label">Attendance Rate</div></div></div>
      </div>
      
      <div class="dashboard-panel">
        <h3 class="recent-students-title">Recent Students</h3>
        <table class="data-table table-full">
          <thead><tr><th>Student</th><th>Class</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($recentStudents as $student): ?>
            <tr>
              <td><?php echo htmlspecialchars($student['name']); ?></td>
              <td>KG1 – Class</td>
              <td>
                <span class="status-badge <?php echo $student['enrollmentStatus'] === 'Active' ? 'status-badge--active' : 'status-badge--alert'; ?>">
                  <?php echo $student['enrollmentStatus'] === 'Active' ? '✅ Active' : '⚠️ ' . htmlspecialchars($student['enrollmentStatus']); ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php elseif ($panel === 'messages'):
            include __DIR__ . '/partials/partial_messages.php';
            elseif ($panel === 'reports'):
            include __DIR__ . '/partials/partial_reports.php';
            elseif ($panel === 'attendance'):
            include __DIR__ . '/partials/partial_attendance.php';
            elseif ($panel === 'profiles'):
            include __DIR__ . '/partials/partial_profiles.php';
          endif; ?>
    </div>
    <!-- /dash-content -->
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
