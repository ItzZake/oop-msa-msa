<?php
// Session & role resolution — before any output (MVC: Controller → View)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Controller/AttendanceController.php';

$controller = new AttendanceController();
$userRole = $_SESSION['user_role'] ?? null;
$attendanceView = $controller->resolveView($userRole);

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($attendanceView === null) {
    $_SESSION['error'] = 'You do not have permission to access the attendance page.';
    header('Location: ' . $controller->getUnauthorizedRedirect($userRole));
    exit;
}

$viewMeta = $controller->getViewMeta($attendanceView);

$teacherPageData = null;
$parentPageData = null;
$adminPageData = null;
if ($attendanceView === 'teacher') {
    $teacherPageData = $controller->loadTeacherPageData((int) $_SESSION['user_id']);
} elseif ($attendanceView === 'parent') {
    $parentPageData = $controller->loadParentPageData((int) $_SESSION['user_id']);
} elseif ($attendanceView === 'admin') {
    $adminPageData = $controller->loadAdminPageData();
    $teacherPageData = $controller->loadTeacherPageData((int) $_SESSION['user_id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($viewMeta['title']); ?> – Wellucation</title>
  <link rel="stylesheet" href="css/Attendance.css" />
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
?>

  <!-- HEADER -->
  <section class="page-header">
    <div class="header-decoration">📋</div>
    <div class="header-inner">
      <span class="header-badge"><?php echo htmlspecialchars($viewMeta['badge']); ?></span>
      <h1 class="header-title"><?php echo htmlspecialchars($viewMeta['title']); ?></h1>
      <p class="header-subtitle"><?php echo htmlspecialchars($viewMeta['subtitle']); ?></p>
    </div>
  </section>

  <!-- CONTENT -->
  <section class="content-area">
    <div class="content-inner">

      <?php if ($attendanceView === 'teacher'): ?>
      <!-- TEACHER VIEW -->
      <div id="view-teacher" class="view-panel active">

        <!-- Controls -->
        <div class="card">
          <h3 class="section-title blue">👩‍🏫 Mark Attendance</h3>
          <div class="controls-grid">
            <div>
              <label class="field-label blue">Select Course</label>
              <div class="select-wrapper">
                <select id="teacher-class-select" class="field-select blue-select" <?php echo empty($teacherPageData['courses']) ? 'disabled' : ''; ?>>
                  <?php if (empty($teacherPageData['courses'])): ?>
                    <option value="">No courses assigned</option>
                  <?php else: ?>
                    <?php foreach ($teacherPageData['courses'] as $course): ?>
                      <option value="<?php echo (int) $course['courseID']; ?>"
                        <?php echo ((int) $course['courseID'] === (int) $teacherPageData['selectedCourseId']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['name']); ?>
                        (<?php echo (int) ($course['enrolledCount'] ?? 0); ?> enrolled)
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <span class="select-arrow">▾</span>
              </div>
            </div>
            <div>
              <label class="field-label blue">Date</label>
              <input type="date" id="teacher-date" class="field-input blue-select" />
            </div>
            <div class="quick-mark-col">
              <label class="field-label blue">Quick Mark</label>
              <button id="mark-all-present" class="btn btn-green">✅ All Present</button>
            </div>
          </div>
        </div>

        <!-- Summary mini cards -->
        <div class="stats-grid-4">
          <div class="stat-card blue-bg">
            <div class="stat-icon">👥</div>
            <div class="stat-value blue" id="stat-total"><?php echo count($teacherPageData['students'] ?? []); ?></div>
            <div class="stat-label">Total</div>
          </div>
          <div class="stat-card green-bg">
            <div class="stat-icon">✅</div>
            <div class="stat-value green" id="stat-present">0</div>
            <div class="stat-label">Present</div>
          </div>
          <div class="stat-card red-bg">
            <div class="stat-icon">❌</div>
            <div class="stat-value red" id="stat-absent">0</div>
            <div class="stat-label">Absent</div>
          </div>
          <div class="stat-card amber-bg">
            <div class="stat-icon">⏰</div>
            <div class="stat-value amber" id="stat-late">0</div>
            <div class="stat-label">Late</div>
          </div>
        </div>

        <!-- Student List -->
        <div class="card no-padding">
          <div class="list-header blue-bg-light">
            <h4 class="list-header-title blue" id="class-header-title">Students</h4>
            <span class="list-header-count" id="marked-count">0/0 marked</span>
          </div>
          <div id="student-list" class="student-list">
            <?php if (empty($teacherPageData['courses'])): ?>
              <p class="empty-list-msg">No courses are assigned to your account yet.</p>
            <?php elseif (empty($teacherPageData['students'])): ?>
              <p class="empty-list-msg">No enrolled students with linked parents in this course.</p>
            <?php endif; ?>
          </div>
          <div class="list-footer">
            <div id="submit-feedback" class="submit-feedback hidden">✅ Attendance submitted successfully!</div>
            <div id="footer-count" class="footer-count">0/0 students marked</div>
            <button id="submit-btn" class="btn btn-blue" disabled>✅ Submit Attendance</button>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($attendanceView === 'parent'): ?>
      <?php
        $parentChildren = $parentPageData['children'] ?? [];
        $parentAtt = $parentPageData['attendance'] ?? null;
        $showChildSwitcher = count($parentChildren) > 1;
      ?>
      <!-- PARENT VIEW -->
      <div id="view-parent" class="view-panel active">

        <?php if ($showChildSwitcher): ?>
        <section class="child-selector-bar">
          <div class="view-selector-inner">
            <label class="field-label" for="parent-child-select" style="margin:0;align-self:center;">Child:</label>
            <div class="select-wrapper" style="min-width:220px;">
              <select id="parent-child-select" class="field-select" style="border-color:#10B981;">
                <?php foreach ($parentChildren as $child): ?>
                  <option value="<?php echo (int) $child['id']; ?>"
                    <?php echo ((int) $child['id'] === (int) ($parentPageData['selectedChildId'] ?? 0)) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($child['emoji'] . ' ' . $child['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <span class="select-arrow" style="color:#10B981;">▾</span>
            </div>
          </div>
        </section>
        <?php endif; ?>

        <?php if (empty($parentChildren)): ?>
        <div class="card">
          <p class="empty-list-msg">No children are linked to your account. Please contact the school to link your child.</p>
        </div>
        <?php else: ?>

        <!-- Child info -->
        <div class="card child-info-card">
          <div class="child-emoji" id="parent-child-emoji"><?php echo htmlspecialchars($parentAtt['child']['emoji'] ?? '👤'); ?></div>
          <div>
            <h3 class="child-name" id="parent-child-name"><?php echo htmlspecialchars($parentAtt['child']['name'] ?? ''); ?></h3>
            <div class="child-class blue" id="parent-child-course">
              <?php
                $courseLabel = htmlspecialchars($parentAtt['child']['courseLabel'] ?? '');
                $age = $parentAtt['child']['age'] ?? null;
                echo $courseLabel . ($age !== null ? ' · Age ' . (int) $age : '');
              ?>
            </div>
            <div class="child-teacher" id="parent-child-teacher">Teacher: <?php echo htmlspecialchars($parentAtt['child']['teacherName'] ?? '—'); ?></div>
          </div>
          <div class="child-rate-box">
            <div class="child-rate-value green" id="parent-rate-value"><?php echo (int) ($parentAtt['stats']['rate'] ?? 0); ?>%</div>
            <div class="child-rate-label">Attendance Rate</div>
          </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid-3">
          <div class="stat-card green-bg">
            <div class="stat-icon">✅</div>
            <div class="stat-value green" id="parent-present"><?php echo (int) ($parentAtt['stats']['present'] ?? 0); ?></div>
            <div class="stat-label">Days Present</div>
          </div>
          <div class="stat-card red-bg">
            <div class="stat-icon">❌</div>
            <div class="stat-value red" id="parent-absent"><?php echo (int) ($parentAtt['stats']['absent'] ?? 0); ?></div>
            <div class="stat-label">Days Absent</div>
          </div>
          <div class="stat-card amber-bg">
            <div class="stat-icon">⏰</div>
            <div class="stat-value amber" id="parent-late"><?php echo (int) ($parentAtt['stats']['late'] ?? 0); ?></div>
            <div class="stat-label">Late Arrivals</div>
          </div>
        </div>

        <!-- Calendar -->
        <div class="card">
          <div class="parent-calendar-header">
            <h4 class="card-subtitle" id="parent-calendar-title">📅 Attendance Calendar – <?php echo htmlspecialchars($parentAtt['monthLabel'] ?? ''); ?></h4>
            <div class="parent-month-nav">
              <button type="button" class="btn btn-green-outline btn-sm" id="parent-month-prev" aria-label="Previous month">‹</button>
              <input type="month" id="parent-month-picker" class="field-input parent-month-input"
                value="<?php echo sprintf('%04d-%02d', $parentPageData['year'] ?? date('Y'), $parentPageData['month'] ?? date('n')); ?>" />
              <button type="button" class="btn btn-green-outline btn-sm" id="parent-month-next" aria-label="Next month">›</button>
            </div>
          </div>
          <div class="calendar-days-header">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
          </div>
          <div class="calendar-grid" id="calendar-grid"></div>
          <div class="calendar-legend">
            <div class="legend-item"><span class="legend-dot green-bg-solid"></span> Present</div>
            <div class="legend-item"><span class="legend-dot red-bg-solid"></span> Absent</div>
            <div class="legend-item"><span class="legend-dot amber-bg-solid"></span> Late</div>
          </div>
        </div>

        <!-- Attendance notice -->
        <div class="notice-box <?php echo empty($parentAtt['notice']['show']) ? 'hidden' : ''; ?>" id="attendance-notice">
          <span class="notice-icon">⚠️</span>
          <div>
            <div class="notice-title" id="notice-title"><?php echo htmlspecialchars($parentAtt['notice']['title'] ?? ''); ?></div>
            <p class="notice-body" id="notice-body"><?php echo htmlspecialchars($parentAtt['notice']['body'] ?? ''); ?></p>
          </div>
        </div>

        <?php endif; ?>
      </div>
      <?php endif; ?>

      <?php if ($attendanceView === 'admin' && $adminPageData): ?>
      <?php $adminSummary = $adminPageData['summary'] ?? []; ?>
      <!-- ADMIN VIEW -->
      <div id="view-admin" class="view-panel active">

        <!-- Filters -->
        <div class="card">
          <h3 class="section-title pink">🛠️ Attendance Reports & Analytics</h3>
          <div class="filters-row">
            <div class="select-wrapper">
              <select id="admin-course-select" class="field-select pink-select">
                <option value="0">All Courses</option>
                <?php foreach ($adminPageData['courses'] as $course): ?>
                  <option value="<?php echo (int) $course['courseID']; ?>"
                    <?php echo ((int) $course['courseID'] === (int) ($adminPageData['selectedCourseId'] ?? 0)) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($course['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <span class="select-arrow pink">▾</span>
            </div>
            <input type="date" id="admin-date-filter" class="field-input pink-select"
              value="<?php echo htmlspecialchars($adminPageData['filterDate'] ?? date('Y-m-d')); ?>" />
            <button type="button" class="btn btn-pink" id="admin-apply-filters">⚙ Apply Filters</button>
            <button type="button" class="btn btn-pink-outline ml-auto" id="admin-export-csv">⬇ Export CSV</button>
          </div>
        </div>

        <!-- Admin summary cards -->
        <div class="stats-grid-4">
          <div class="stat-card green-bg stat-card-row">
            <div class="stat-icon">📊</div>
            <div>
              <div class="stat-value green" id="admin-school-rate"><?php echo (int) ($adminSummary['schoolWideRate'] ?? 0); ?>%</div>
              <div class="stat-label">School-Wide Rate</div>
            </div>
          </div>
          <div class="stat-card blue-bg stat-card-row">
            <div class="stat-icon">✅</div>
            <div>
              <div class="stat-value blue" id="admin-present-today"><?php echo (int) ($adminSummary['presentToday'] ?? 0); ?></div>
              <div class="stat-label">Total Present (Date)</div>
            </div>
          </div>
          <div class="stat-card red-bg stat-card-row">
            <div class="stat-icon">❌</div>
            <div>
              <div class="stat-value red" id="admin-absent-today"><?php echo (int) ($adminSummary['absentToday'] ?? 0); ?></div>
              <div class="stat-label">Total Absent (Date)</div>
            </div>
          </div>
          <div class="stat-card amber-bg stat-card-row">
            <div class="stat-icon">⚠️</div>
            <div>
              <div class="stat-value amber" id="admin-below-85"><?php echo (int) ($adminSummary['classesBelow85'] ?? 0); ?></div>
              <div class="stat-label">Courses Below 85%</div>
            </div>
          </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
          <div class="card">
            <h4 class="card-subtitle" id="admin-daily-chart-title">Daily Attendance (<?php echo htmlspecialchars($adminPageData['chartMonthLabel'] ?? ''); ?>)</h4>
            <p class="card-desc">Number of students per day</p>
            <canvas id="daily-chart" height="200"></canvas>
          </div>
          <div class="card">
            <h4 class="card-subtitle">Monthly Attendance Rate</h4>
            <p class="card-desc">School-wide attendance % per month (last 6 months)</p>
            <canvas id="monthly-chart" height="200"></canvas>
          </div>
        </div>

        <!-- Course Reports Table -->
        <div class="card no-padding">
          <div class="list-header pink-bg-light">
            <h4 class="list-header-title pink">📋 Course Attendance Reports</h4>
            <span class="list-header-count" id="admin-report-date"><?php echo htmlspecialchars($adminPageData['reportDateLabel'] ?? ''); ?></span>
          </div>
          <div class="table-wrapper">
            <table class="reports-table">
              <thead>
                <tr>
                  <th>Course</th><th>Teacher</th><th>Enrolled</th><th>Present</th><th>Absent</th><th>Late</th><th>Rate</th>
                </tr>
              </thead>
              <tbody id="admin-reports-body">
                <?php if (empty($adminPageData['courseReports'])): ?>
                  <tr><td colspan="7" class="empty-list-msg">No course reports for this date.</td></tr>
                <?php else: ?>
                  <?php foreach ($adminPageData['courseReports'] as $report): ?>
                    <tr>
                      <td class="td-bold"><?php echo htmlspecialchars($report['courseName']); ?></td>
                      <td class="td-muted"><?php echo htmlspecialchars($report['teacherName']); ?></td>
                      <td class="td-bold"><?php echo (int) $report['totalStudents']; ?></td>
                      <td><span class="green"><?php echo (int) $report['present']; ?></span></td>
                      <td><span class="red"><?php echo (int) $report['absent']; ?></span></td>
                      <td><span class="amber"><?php echo (int) $report['late']; ?></span></td>
                      <td><span class="rate-badge <?php echo htmlspecialchars($report['rateClass']); ?>"><?php echo (int) $report['rate']; ?>%</span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- end admin -->
      <?php endif; ?>
    </div>
  </section>

  <script>
    const currentUserRole = <?php echo json_encode($attendanceView, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const attendanceApiBase = '../Controller/AttendanceDataController.php';
    <?php if ($attendanceView === 'teacher' && $teacherPageData): ?>
    const attendanceBootstrap = <?php echo json_encode([
        'courses'          => $teacherPageData['courses'],
        'selectedCourseId' => $teacherPageData['selectedCourseId'],
        'sessionDate'      => $teacherPageData['sessionDate'],
        'students'         => $teacherPageData['students'],
        'apiBase'          => '../Controller/AttendanceDataController.php',
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    <?php endif; ?>
    <?php if ($attendanceView === 'parent' && $parentPageData): ?>
    const parentAttendanceBootstrap = <?php echo json_encode([
        'children'        => $parentPageData['children'],
        'selectedChildId' => $parentPageData['selectedChildId'],
        'year'            => $parentPageData['year'],
        'month'           => $parentPageData['month'],
        'attendance'      => $parentPageData['attendance'],
        'apiBase'         => '../Controller/AttendanceDataController.php',
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    <?php endif; ?>
    <?php if ($attendanceView === 'admin' && $adminPageData): ?>
    const adminAttendanceBootstrap = <?php echo json_encode(array_merge($adminPageData, [
        'apiBase' => '../Controller/AttendanceDataController.php',
    ]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    <?php endif; ?>
  </script>
  <?php if ($attendanceView === 'admin'): ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php endif; ?>
  <script src="scripts/Attendance.js"></script>
</body>
</html>
