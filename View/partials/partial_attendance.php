<?php
// Inline student dataset used by the dashboard attendance panel
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

<section class="page-hero">
  <div class="page-hero__content">
    <span class="page-badge page-badge--blue">📋 Attendance Management</span>
    <h1 class="page-hero__title">Attendance Tracking System</h1>
    <p class="page-hero__subtitle">Manage and monitor student attendance with real-time tracking, analytics, and smart reports.</p>
  </div>
</section>

<div class="view-tabs">
  <div class="view-tabs-inner">
    <button class="view-tab-btn view-tab-btn--active" onclick="setAttView('teacher')">👩‍🏫 Teacher View</button>
    <button class="view-tab-btn" onclick="setAttView('parent')">👨‍👩‍👧 Parent View</button>
    <button class="view-tab-btn" onclick="setAttView('admin')">🛡️ Admin View</button>
  </div>
</div>

<section class="section section--gray">
  <div class="container">
    <!-- TEACHER VIEW -->
    <div id="att-view-teacher">
      <form method="POST" action="submit_attendance.php" id="attendanceForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
        
        <div class="attendance-panel">
          <h3>👩‍🏫 Mark Attendance</h3>
          <div class="attendance-controls">
            <div><label class="form-label">Select Class</label><select class="form-select" name="class"><option>🌻 KG1 – Sunflower</option><option>🌈 KG2 – Rainbow</option><option>🦋 Nursery – Butterfly</option><option>⭐ KG2 – Stars</option></select></div>
            <div><label class="form-label">Date</label><input type="date" name="date_display" class="form-input" value="<?php echo date('Y-m-d'); ?>"></div>
            <div class="attendance-action-row"><button type="button" class="btn btn-success" onclick="markAllPresent()">✅ Mark All Present</button></div>
          </div>
        </div>
        
        <div class="attendance-panel attendance-panel--students">
          <div class="attendance-panel-header">
            <h4>Students – KG1 Sunflower</h4>
            <span class="attendance-marked" id="markedCount">0/<?php echo count($students); ?> marked</span>
          </div>
          <div id="studentRows" class="attendance-student-list">
            <?php foreach ($students as $index => $student): ?>
            <div class="attendance-row">
              <div class="attendance-row-info"><span class="attendance-row-emoji"><?php echo $student['emoji']; ?></span><div><div class="attendance-row-name"><?php echo htmlspecialchars($student['name']); ?></div><div class="attendance-row-meta"><?php echo htmlspecialchars($student['class']); ?></div></div></div>
              <div class="attendance-row-actions">
                <button type="button" onclick="setStatus(<?php echo $index; ?>, 'present')" data-student="<?php echo $student['id']; ?>" class="status-btn status-present">✅</button>
                <button type="button" onclick="setStatus(<?php echo $index; ?>, 'absent')" class="status-btn status-absent">❌</button>
                <button type="button" onclick="setStatus(<?php echo $index; ?>, 'late')" class="status-btn status-late">⏰</button>
              </div>
              <input type="hidden" name="attendance[<?php echo $student['id']; ?>]" id="status_<?php echo $index; ?>" value="">
            </div>
            <?php endforeach; ?>
          </div>
          <div class="attendance-panel-footer">
            <span class="attendance-marked" id="markedCount2">0/<?php echo count($students); ?> marked</span>
            <button type="submit" id="submitAttBtn" class="btn btn-primary attendance-submit" disabled>✅ Submit Attendance</button>
          </div>
        </div>
      </form>
    </div>
    
    <!-- PARENT VIEW (simplified) -->
    <div id="att-view-parent" class="is-hidden">
      <div class="attendance-panel">
        <h3>Parent View - Student Attendance</h3>
        <p>View your child's attendance records here.</p>
      </div>
    </div>
    
    <!-- ADMIN VIEW (simplified) -->
    <div id="att-view-admin" class="is-hidden">
      <div class="attendance-panel">
        <h3>Admin View - School-wide Reports</h3>
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
    
    const row = document.querySelector(`#studentRows > div:nth-child(${index + 1})`);
    if (row) {
        const btns = row.querySelectorAll('.status-btn');
        btns.forEach(btn => btn.classList.remove('status-active'));
        const activeBtn = row.querySelector(`.status-${status}`);
        if (activeBtn) activeBtn.classList.add('status-active');
    }
    updateAttStats();
}

function markAllPresent() {
    for (let i = 0; i < studentStatus.length; i++) {
        setStatus(i, 'present');
    }
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
