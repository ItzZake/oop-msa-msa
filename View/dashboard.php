<?php
session_start();
$pageTitle = "Dashboard – Wellucation Nursery";
$currentPage = "dashboard";
$pageCss = 'dashboard.css';

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
	<link rel="stylesheet" href="../view/css/dashboard.css" />
	<link rel="stylesheet" href="../view/css/home.css" />
</head>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="dashboard-sidebar-header">
      <span class="dashboard-topbar-label">Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
      <a href="dashboard.php?panel=overview" class="sidebar-nav-btn <?php echo $panel === 'overview' ? 'active' : ''; ?>">🏠 <span>Overview</span></a>
      <a href="dashboard.php?panel=students" class="sidebar-nav-btn <?php echo $panel === 'students' ? 'active' : ''; ?>">👥 <span>Students</span></a>
      <a href="dashboard.php?panel=teachers" class="sidebar-nav-btn <?php echo $panel === 'teachers' ? 'active' : ''; ?>">🎓 <span>Teachers</span></a>
      <a href="dashboard.php?panel=attendance" class="sidebar-nav-btn <?php echo $panel === 'attendance' ? 'active' : ''; ?>">📅 <span>Attendance</span></a>
      <a href="dashboard.php?panel=messages" class="sidebar-nav-btn <?php echo $panel === 'messages' ? 'active' : ''; ?>">💬 <span>Messages</span></a>
      <a href="dashboard.php?panel=reports" class="sidebar-nav-btn <?php echo $panel === 'reports' ? 'active' : ''; ?>">📊 <span>Reports</span></a>
      <a href="dashboard.php?panel=profiles" class="sidebar-nav-btn <?php echo $panel === 'profiles' ? 'active' : ''; ?>">👥 <span>Profiles</span></a>
      <a href="dashboard.php?panel=analytics" class="sidebar-nav-btn <?php echo $panel === 'analytics' ? 'active' : ''; ?>">📈 <span>Analytics</span></a>
    </nav>
    <div class="dashboard-quick-action">
      <div class="dashboard-quick-action-title">Quick Action</div>
      <a href="add_user.php">+ Add Student</a>
    </div>
  </aside>
  
  <main class="dashboard-main">
    <div class="dashboard-topbar">
      <div>
        <div class="dashboard-topbar-label"><?php echo ucfirst($panel); ?></div>
        <h2 class="dashboard-topbar-title">Admin Dashboard</h2>
      </div>
      <div class="dashboard-topbar-avatar">
        <div class="dashboard-avatar-badge">A</div>
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
