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

// Import database models
require_once '../Models/Database.php';

// Initialize variables
$totalStudents = 248;
$totalTeachers = 18;
$attendanceRate = 94;
$activeAlerts = 3;
$activeClasses = 12;
$enrollmentRate = 96;
$recentStudents = [];

try {
    $db = Database::getInstance();
    
    // Count total students
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM Child");
    $totalStudents = $result['count'] ?? 248;
    
    // Count total teachers
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM Teacher");
    $totalTeachers = $result['count'] ?? 18;
    
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
        LIMIT 5
    ");
    
} catch (Exception $e) {
    // Use default values if database fails
    $recentStudents = [
        ['childID' => 1, 'name' => 'Emma Johnson', 'gender' => 'Female', 'enrollmentStatus' => 'Active'],
        ['childID' => 2, 'name' => 'Noah Williams', 'gender' => 'Male', 'enrollmentStatus' => 'Active'],
        ['childID' => 3, 'name' => 'Sophia Brown', 'gender' => 'Female', 'enrollmentStatus' => 'Active'],
        ['childID' => 4, 'name' => 'Liam Davis', 'gender' => 'Male', 'enrollmentStatus' => 'Active'],
        ['childID' => 5, 'name' => 'Olivia Wilson', 'gender' => 'Female', 'enrollmentStatus' => 'Active'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
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

    <!-- CONTENT AREA -->
    <div class="dash-content" id="dashContent">
      <!-- ── OVERVIEW ── -->
      <section class="dash-section active" id="section-overview" <?php echo $panel !== 'overview' ? 'style="display:none;"' : ''; ?>>
        <!-- Welcome Banner -->
        <div class="welcome-banner animate-fade-up">
          <div>
            <div class="welcome-banner__title">Good morning, Admin! 👋</div>
            <div class="welcome-banner__sub">Here's what's happening at Wellucation today.</div>
          </div>
          <div class="welcome-banner__emoji">🏫</div>
        </div>

        <!-- Overview Cards -->
        <div class="overview-cards" id="overviewCards">
          <div class="ov-card animate-fade-up" style="--clr: #1565c0; --bg: #eff6ff">
            <div class="ov-card__top">
              <div class="ov-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                  <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
              </div>
            </div>
            <div class="ov-card__value"><?php echo htmlspecialchars($totalStudents); ?></div>
            <div class="ov-card__label">Total Students</div>
            <div class="ov-card__change">+12 this month</div>
          </div>

          <div class="ov-card animate-fade-up" style="--clr: #e91e8c; --bg: #fff0f7">
            <div class="ov-card__top">
              <div class="ov-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                  <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
              </div>
            </div>
            <div class="ov-card__value"><?php echo htmlspecialchars($totalTeachers); ?></div>
            <div class="ov-card__label">Total Teachers</div>
            <div class="ov-card__change">+1 new hire</div>
          </div>

          <div class="ov-card animate-fade-up" style="--clr: #10b981; --bg: #f0fdf4">
            <div class="ov-card__top">
              <div class="ov-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                  <line x1="16" x2="16" y1="2" y2="6" />
                  <line x1="8" x2="8" y1="2" y2="6" />
                  <line x1="3" x2="21" y1="10" y2="10" />
                  <path d="m9 16 2 2 4-4" />
                </svg>
              </div>
            </div>
            <div class="ov-card__value"><?php echo htmlspecialchars($attendanceRate); ?>%</div>
            <div class="ov-card__label">Today's Attendance</div>
            <div class="ov-card__change">234 / 248 present</div>
          </div>

          <div class="ov-card animate-fade-up" style="--clr: #f59e0b; --bg: #fffbeb">
            <div class="ov-card__top">
              <div class="ov-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                  <path d="M12 9v4" />
                  <path d="M12 17h.01" />
                </svg>
              </div>
            </div>
            <div class="ov-card__value"><?php echo $activeAlerts; ?></div>
            <div class="ov-card__label">Active Alerts</div>
            <div class="ov-card__change">2 require action</div>
          </div>

          <div class="ov-card animate-fade-up" style="--clr: #8b5cf6; --bg: #f5f3ff">
            <div class="ov-card__top">
              <div class="ov-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                  <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                </svg>
              </div>
            </div>
            <div class="ov-card__value"><?php echo $activeClasses; ?></div>
            <div class="ov-card__label">Active Classes</div>
            <div class="ov-card__change">4 programs running</div>
          </div>

          <div class="ov-card animate-fade-up" style="--clr: #ec4899; --bg: #fff0f7">
            <div class="ov-card__top">
              <div class="ov-card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                  <polyline points="16 7 22 7 22 13" />
                </svg>
              </div>
            </div>
            <div class="ov-card__value"><?php echo $enrollmentRate; ?>%</div>
            <div class="ov-card__label">Enrollment Rate</div>
            <div class="ov-card__change">vs 91% last year</div>
          </div>
        </div>

        <!-- Bottom Row: Students table + Alerts -->
        <div class="bottom-row">
          <!-- Student Table -->
          <div class="students-table-card animate-fade-up">
            <div class="students-table-card__head">
              <h3 class="chart-card__title">Recent Students</h3>
              <a href="add_user.php" class="btn-add-student">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14" />
                  <path d="M12 5v14" />
                </svg>
                Add Student
              </a>
            </div>
            <div class="table-scroll">
              <table class="students-table">
                <thead>
                  <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Attendance</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recentStudents as $student): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td>KG1 – Class</td>
                    <td><span class="attendance-badge">94%</span></td>
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
          </div>

          <!-- Alerts -->
          <div class="alerts-card animate-fade-up">
            <div class="alerts-card__head">
              <h3 class="chart-card__title">Alerts</h3>
              <span class="alerts-badge"><?php echo $activeAlerts; ?></span>
            </div>
            <div class="alerts-list">
              <div class="alert-item alert-item--warning">
                <div class="alert-item__icon">⚠️</div>
                <div class="alert-item__content">
                  <div class="alert-item__title">Low Attendance</div>
                  <div class="alert-item__text">3 students below 80% threshold</div>
                </div>
              </div>
              <div class="alert-item alert-item--info">
                <div class="alert-item__icon">ℹ️</div>
                <div class="alert-item__content">
                  <div class="alert-item__title">Payment Pending</div>
                  <div class="alert-item__text">5 outstanding invoices</div>
                </div>
              </div>
              <div class="alert-item alert-item--success">
                <div class="alert-item__icon">✅</div>
                <div class="alert-item__content">
                  <div class="alert-item__title">System Update</div>
                  <div class="alert-item__text">All systems operational</div>
                </div>
              </div>
            </div>

            <div class="quick-actions">
              <h4 class="quick-actions__title">Quick Actions</h4>
              <div class="quick-actions__grid">
                <a href="add_user.php" class="qa-btn" style="--clr: #1565c0; --bg: #eff6ff">
                  <span class="qa-btn__emoji">👦</span>Add Student
                </a>
                <a href="reports.php" class="qa-btn" style="--clr: #e91e8c; --bg: #fff0f7">
                  <span class="qa-btn__emoji">📄</span>New Report
                </a>
                <a href="messages.php" class="qa-btn" style="--clr: #10b981; --bg: #f0fdf4">
                  <span class="qa-btn__emoji">📢</span>Send Notice
                </a>
                <a href="schedule.php" class="qa-btn" style="--clr: #f59e0b; --bg: #fffbeb">
                  <span class="qa-btn__emoji">📅</span>Schedule
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ── PLACEHOLDER SECTIONS ── -->
      <section class="dash-section placeholder-section" id="section-students" <?php echo $panel !== 'students' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">👩‍🎓</div>
          <h2>Students</h2>
          <p>Full student management coming soon.</p>
        </div>
      </section>

      <section class="dash-section placeholder-section" id="section-teachers" <?php echo $panel !== 'teachers' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">🎓</div>
          <h2>Teachers</h2>
          <p>Teacher management coming soon.</p>
        </div>
      </section>

      <section class="dash-section placeholder-section" id="section-attendance" <?php echo $panel !== 'attendance' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">📅</div>
          <h2>Attendance</h2>
          <p>Detailed attendance tracking coming soon.</p>
        </div>
      </section>

      <section class="dash-section placeholder-section" id="section-analytics" <?php echo $panel !== 'analytics' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">📊</div>
          <h2>Analytics</h2>
          <p>Advanced analytics coming soon.</p>
        </div>
      </section>

      <section class="dash-section placeholder-section" id="section-reports" <?php echo $panel !== 'reports' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">📋</div>
          <h2>Reports</h2>
          <p>Report generation coming soon.</p>
        </div>
      </section>

      <section class="dash-section placeholder-section" id="section-notifications" <?php echo $panel !== 'messages' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">💬</div>
          <h2>Messages</h2>
          <p>Message center coming soon.</p>
        </div>
      </section>

      <section class="dash-section placeholder-section" id="section-settings" <?php echo $panel !== 'profiles' ? 'style="display:none;"' : ''; ?>>
        <div class="placeholder-inner">
          <div class="placeholder-emoji">👤</div>
          <h2>Profiles</h2>
          <p>User profiles coming soon.</p>
        </div>
      </section>
    </div>
    <!-- /dash-content -->
  </main>
</div>
<!-- /dashboard-wrapper -->

<!-- ══════════════ FOOTER ══════════════ -->
<footer class="footer">
  <div class="container footer__grid">
    <div class="footer__col">
      <div class="footer__brand">
        <div class="footer__logo-img">
          <span style="font-size:1.4rem">🌟</span>
        </div>
        <div>
          <div class="footer__brand-name">Wellucation</div>
          <div class="footer__brand-tagline">Learn. Play. Grow</div>
        </div>
      </div>
      <p class="footer__desc">Nurturing young minds with love, creativity, and excellence in early childhood education.</p>
    </div>
    <div class="footer__col">
      <h4 class="footer__col-title">Quick Links</h4>
      <nav class="footer__links">
        <a href="home.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="dashboard.php">Dashboard</a>
      </nav>
    </div>
    <div class="footer__col">
      <h4 class="footer__col-title">Support</h4>
      <nav class="footer__links">
        <a href="#">Help Center</a>
        <a href="#">Documentation</a>
        <a href="#">FAQ</a>
        <a href="#">Contact Support</a>
      </nav>
    </div>
    <div class="footer__col">
      <h4 class="footer__col-title">Legal</h4>
      <nav class="footer__links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
        <a href="#">Disclaimer</a>
      </nav>
    </div>
  </div>
  <div class="footer__bottom">
    <p>&copy; 2025 Wellucation. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
</div>

<script src="scripts/dashboard.js"></script>

<?php include 'footer.php'; ?>
