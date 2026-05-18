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
?>

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
        <div class="overview-card overview-card--metric"><div><div class="metric-value metric-value--blue">248</div><div class="metric-label">Total Students</div></div></div>
        <div class="overview-card overview-card--metric"><div><div class="metric-value metric-value--pink">18</div><div class="metric-label">Total Teachers</div></div></div>
        <div class="overview-card overview-card--metric"><div><div class="metric-value metric-value--green">94%</div><div class="metric-label">Attendance Rate</div></div></div>
      </div>
      
      <div class="dashboard-panel">
        <h3 class="recent-students-title">Recent Students</h3>
        <table class="data-table table-full">
          <thead><tr><th>Student</th><th>Class</th><th>Status</th></tr></thead>
          <tbody>
            <tr><td>Emma Johnson</td><td>KG1 – Sunflower</td><td><span class="status-badge status-badge--active">✅ Active</span></td></tr>
            <tr><td>Noah Williams</td><td>KG2 – Rainbow</td><td><span class="status-badge status-badge--active">✅ Active</span></td></tr>
            <tr><td>Sophia Brown</td><td>Nursery – Butterfly</td><td><span class="status-badge status-badge--alert">⚠️ Concern</span></td></tr>
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
