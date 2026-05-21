/* ── STATE (teacher view) ── */
let students = [];
let attendance = {};
let selectedCourseId = 0;
let sessionDate = new Date().toISOString().split('T')[0];
const apiBase = (typeof attendanceBootstrap !== 'undefined' && attendanceBootstrap.apiBase)
  ? attendanceBootstrap.apiBase
  : '../Controller/AttendanceDataController.php';

const activeRole = (typeof currentUserRole !== 'undefined' && currentUserRole)
  ? String(currentUserRole).toLowerCase()
  : '';

const parentApiBase = (typeof parentAttendanceBootstrap !== 'undefined' && parentAttendanceBootstrap.apiBase)
  ? parentAttendanceBootstrap.apiBase
  : (typeof attendanceApiBase !== 'undefined' ? attendanceApiBase : '../Controller/AttendanceDataController.php');

let parentSelectedChildId = 0;
let parentYear = new Date().getFullYear();
let parentMonth = new Date().getMonth() + 1;
let parentAttendanceData = null;

function applyStudentsFromApi(list) {
  students = (list || []).map(s => ({
    id: s.id,
    name: s.name,
    emoji: s.emoji || '👤',
    cls: s.cls || '',
  }));

  attendance = {};
  (list || []).forEach(s => {
    if (s.existingStatus) {
      attendance[s.id] = s.existingStatus;
    }
  });
}

function getSelectedCourseName() {
  const select = document.getElementById('teacher-class-select');
  if (!select || select.selectedIndex < 0) return '';
  return select.options[select.selectedIndex].text.replace(/\s*\(\d+\s+enrolled\)\s*$/, '').trim();
}

function updateHeaderTitle() {
  const title = document.getElementById('class-header-title');
  const courseName = getSelectedCourseName();
  if (title) {
    title.textContent = courseName ? `Students – ${courseName}` : 'Students';
  }
}

/* ── TEACHER VIEW ── */
function renderStudentList() {
  const list = document.getElementById('student-list');
  if (!list) return;

  if (students.length === 0) {
    list.innerHTML = '<p class="empty-list-msg">No enrolled students with linked parents in this course.</p>';
    updateStats();
    return;
  }

  list.innerHTML = '';
  students.forEach(s => {
    const status = attendance[s.id] || null;
    const row = document.createElement('div');
    row.className = 'student-row';
    row.innerHTML = `
      <span class="student-emoji">${s.emoji}</span>
      <div class="student-info">
        <div class="student-name">${escapeHtml(s.name)}</div>
        <div class="student-class">${escapeHtml(s.cls)}</div>
      </div>
      <div class="student-btns">
        <button type="button" class="status-btn ${status === 'present' ? 'btn-present-on' : 'btn-present-off'}" data-id="${s.id}" data-status="present">✓ Present</button>
        <button type="button" class="status-btn ${status === 'absent'  ? 'btn-absent-on'  : 'btn-absent-off'}"  data-id="${s.id}" data-status="absent">✗ Absent</button>
        <button type="button" class="status-btn ${status === 'late'    ? 'btn-late-on'    : 'btn-late-off'}"    data-id="${s.id}" data-status="late">⏰ Late</button>
      </div>
    `;
    list.appendChild(row);
  });

  list.querySelectorAll('.status-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = parseInt(btn.dataset.id, 10);
      const st = btn.dataset.status;
      attendance[id] = attendance[id] === st ? null : st;
      updateStats();
      renderStudentList();
    });
  });

  updateStats();
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

function updateStats() {
  const total = students.length;
  const vals = Object.values(attendance);
  const present = vals.filter(v => v === 'present').length;
  const absent  = vals.filter(v => v === 'absent').length;
  const late    = vals.filter(v => v === 'late').length;
  const marked  = vals.filter(Boolean).length;

  const statTotal   = document.getElementById('stat-total');
  const statPresent = document.getElementById('stat-present');
  const statAbsent  = document.getElementById('stat-absent');
  const statLate    = document.getElementById('stat-late');
  const markedCount = document.getElementById('marked-count');
  const footerCount = document.getElementById('footer-count');
  const submitBtn   = document.getElementById('submit-btn');

  if (statTotal)   statTotal.textContent = total;
  if (statPresent) statPresent.textContent = present;
  if (statAbsent)  statAbsent.textContent  = absent;
  if (statLate)    statLate.textContent    = late;
  if (markedCount) markedCount.textContent = `${marked}/${total} marked`;
  if (footerCount) footerCount.textContent = `${marked}/${total} students marked`;
  if (submitBtn)   submitBtn.disabled = marked === 0 || total === 0;
}

async function fetchStudentsForCourse(courseId, date) {
  const url = `${apiBase}?action=students&courseId=${encodeURIComponent(courseId)}&date=${encodeURIComponent(date)}`;
  const response = await fetch(url, {
    method: 'GET',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
  });

  const result = await response.json();
  if (!response.ok || !result.success) {
    throw new Error(result.error || 'Failed to load students');
  }

  return result.data || [];
}

async function loadCourseStudents() {
  const select = document.getElementById('teacher-class-select');
  if (!select || !selectedCourseId) {
    students = [];
    attendance = {};
    renderStudentList();
    return;
  }

  const list = document.getElementById('student-list');
  if (list) {
    list.innerHTML = '<p class="empty-list-msg">Loading students…</p>';
  }

  try {
    const data = await fetchStudentsForCourse(selectedCourseId, sessionDate);
    applyStudentsFromApi(data);
    updateHeaderTitle();
    renderStudentList();
  } catch (err) {
    console.error(err);
    if (list) {
      list.innerHTML = `<p class="empty-list-msg">${escapeHtml(err.message || 'Could not load students.')}</p>`;
    }
    students = [];
    attendance = {};
    updateStats();
  }
}

async function submitAttendance() {
  const marks = {};
  Object.entries(attendance).forEach(([childId, status]) => {
    if (status) marks[childId] = status;
  });

  if (!selectedCourseId || Object.keys(marks).length === 0) {
    return;
  }

  const submitBtn = document.getElementById('submit-btn');
  if (submitBtn) submitBtn.disabled = true;

  try {
    const response = await fetch(`${apiBase}?action=submit`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        courseId: selectedCourseId,
        date: sessionDate,
        marks,
      }),
    });

    const result = await response.json();
    if (!response.ok || !result.success) {
      throw new Error(result.error || 'Failed to save attendance');
    }

    const feedback = document.getElementById('submit-feedback');
    const counter  = document.getElementById('footer-count');
    if (feedback) {
      feedback.textContent = '✅ ' + (result.message || 'Attendance saved successfully!');
      feedback.classList.remove('hidden');
    }
    if (counter) counter.classList.add('hidden');
    setTimeout(() => {
      if (feedback) feedback.classList.add('hidden');
      if (counter) counter.classList.remove('hidden');
    }, 3000);
  } catch (err) {
    console.error(err);
    alert(err.message || 'Could not save attendance.');
  } finally {
    updateStats();
  }
}

function initTeacherView() {
  if (typeof attendanceBootstrap !== 'undefined') {
    selectedCourseId = parseInt(attendanceBootstrap.selectedCourseId, 10) || 0;
    sessionDate = attendanceBootstrap.sessionDate || sessionDate;
    applyStudentsFromApi(attendanceBootstrap.students || []);
  }

  const classSelect = document.getElementById('teacher-class-select');
  const dateInput   = document.getElementById('teacher-date');
  const markAllBtn  = document.getElementById('mark-all-present');
  const submitBtn   = document.getElementById('submit-btn');

  if (dateInput) {
    dateInput.value = sessionDate;
    dateInput.addEventListener('change', e => {
      sessionDate = e.target.value;
      loadCourseStudents();
    });
  }

  if (classSelect) {
    classSelect.addEventListener('change', e => {
      selectedCourseId = parseInt(e.target.value, 10) || 0;
      loadCourseStudents();
    });
  }

  if (markAllBtn) {
    markAllBtn.addEventListener('click', () => {
      students.forEach(s => { attendance[s.id] = 'present'; });
      renderStudentList();
    });
  }

  if (submitBtn) {
    submitBtn.addEventListener('click', submitAttendance);
  }

  updateHeaderTitle();
  renderStudentList();
}

/* ── PARENT VIEW ── */
function renderParentCalendar(calendar) {
  const grid = document.getElementById('calendar-grid');
  if (!grid) return;

  grid.innerHTML = '';
  (calendar || []).forEach(d => {
    const cell = document.createElement('div');
    const status = d.status || 'empty';
    cell.className = 'cal-cell cal-' + status;
    const label = status === 'empty' ? 'No record' : status.charAt(0).toUpperCase() + status.slice(1);
    cell.title = label;
    cell.textContent = d.date;
    grid.appendChild(cell);
  });
}

function applyParentAttendanceData(data) {
  if (!data) return;

  parentAttendanceData = data;
  const child = data.child || {};
  const stats = data.stats || {};
  const notice = data.notice || {};

  const emojiEl = document.getElementById('parent-child-emoji');
  const nameEl = document.getElementById('parent-child-name');
  const courseEl = document.getElementById('parent-child-course');
  const teacherEl = document.getElementById('parent-child-teacher');
  const rateEl = document.getElementById('parent-rate-value');
  const titleEl = document.getElementById('parent-calendar-title');

  if (emojiEl) emojiEl.textContent = child.emoji || '👤';
  if (nameEl) nameEl.textContent = child.name || '';
  if (courseEl) {
    const agePart = child.age != null ? ` · Age ${child.age}` : '';
    courseEl.textContent = (child.courseLabel || '') + agePart;
  }
  if (teacherEl) teacherEl.textContent = 'Teacher: ' + (child.teacherName || '—');
  if (rateEl) {
    rateEl.textContent = (stats.rate ?? 0) + '%';
    rateEl.className = 'child-rate-value ' + ((stats.rate ?? 0) >= 90 ? 'green' : 'amber');
  }
  if (titleEl) titleEl.textContent = '📅 Attendance Calendar – ' + (data.monthLabel || '');

  const parentPresent = document.getElementById('parent-present');
  const parentAbsent  = document.getElementById('parent-absent');
  const parentLate    = document.getElementById('parent-late');
  if (parentPresent) parentPresent.textContent = stats.present ?? 0;
  if (parentAbsent)  parentAbsent.textContent  = stats.absent ?? 0;
  if (parentLate)    parentLate.textContent    = stats.late ?? 0;

  const noticeBox = document.getElementById('attendance-notice');
  const noticeTitle = document.getElementById('notice-title');
  const noticeBody = document.getElementById('notice-body');
  if (noticeBox) {
    if (notice.show) {
      noticeBox.classList.remove('hidden');
      if (noticeTitle) noticeTitle.textContent = notice.title || 'Attendance Notice';
      if (noticeBody) noticeBody.textContent = notice.body || '';
    } else {
      noticeBox.classList.add('hidden');
    }
  }

  renderParentCalendar(data.calendar || []);
}

async function fetchParentAttendance(childId, year, month) {
  const url = `${parentApiBase}?action=attendance&childId=${encodeURIComponent(childId)}&year=${year}&month=${month}&_t=${Date.now()}`;
  const response = await fetch(url, {
    method: 'GET',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
  });
  const result = await response.json();
  if (!response.ok || !result.success) {
    throw new Error(result.error || 'Failed to load attendance');
  }
  return result.data;
}

function syncParentMonthPicker() {
  const picker = document.getElementById('parent-month-picker');
  if (picker) {
    picker.value = `${parentYear}-${String(parentMonth).padStart(2, '0')}`;
  }
}

async function loadParentAttendance() {
  if (!parentSelectedChildId) return;

  const grid = document.getElementById('calendar-grid');
  const titleEl = document.getElementById('parent-calendar-title');
  
  // Add a subtle loading indicator to the title
  if (titleEl && titleEl.dataset.originalText === undefined) {
    titleEl.dataset.originalText = titleEl.textContent;
  }

  try {
    const data = await fetchParentAttendance(parentSelectedChildId, parentYear, parentMonth);
    applyParentAttendanceData(data);
    syncParentMonthPicker();
  } catch (err) {
    console.error(err);
    if (grid) {
      grid.innerHTML = `<p class="empty-list-msg">${escapeHtml(err.message || 'Could not load attendance.')}</p>`;
    }
  }
}

function shiftParentMonth(delta) {
  parentMonth += delta;
  if (parentMonth > 12) {
    parentMonth = 1;
    parentYear++;
  } else if (parentMonth < 1) {
    parentMonth = 12;
    parentYear--;
  }
  syncParentMonthPicker();
  loadParentAttendance();
}

let parentAutoRefreshInterval = null;
const PARENT_AUTO_REFRESH_INTERVAL_MS = 30000; // 30 seconds

function startParentAutoRefresh() {
  if (parentAutoRefreshInterval) {
    clearInterval(parentAutoRefreshInterval);
  }

  parentAutoRefreshInterval = setInterval(() => {
    if (parentSelectedChildId && activeRole === 'parent') {
      loadParentAttendance();
    }
  }, PARENT_AUTO_REFRESH_INTERVAL_MS);
}

function stopParentAutoRefresh() {
  if (parentAutoRefreshInterval) {
    clearInterval(parentAutoRefreshInterval);
    parentAutoRefreshInterval = null;
  }
}

function initParentView() {
  if (typeof parentAttendanceBootstrap === 'undefined') return;

  parentSelectedChildId = parseInt(parentAttendanceBootstrap.selectedChildId, 10) || 0;
  parentYear = parseInt(parentAttendanceBootstrap.year, 10) || parentYear;
  parentMonth = parseInt(parentAttendanceBootstrap.month, 10) || parentMonth;

  if (parentAttendanceBootstrap.attendance) {
    applyParentAttendanceData(parentAttendanceBootstrap.attendance);
  }

  const childSelect = document.getElementById('parent-child-select');
  const monthPicker = document.getElementById('parent-month-picker');
  const prevBtn = document.getElementById('parent-month-prev');
  const nextBtn = document.getElementById('parent-month-next');

  if (childSelect) {
    childSelect.addEventListener('change', e => {
      parentSelectedChildId = parseInt(e.target.value, 10) || 0;
      loadParentAttendance();
    });
  }

  if (monthPicker) {
    syncParentMonthPicker();
    monthPicker.addEventListener('change', e => {
      const parts = (e.target.value || '').split('-');
      if (parts.length === 2) {
        parentYear = parseInt(parts[0], 10);
        parentMonth = parseInt(parts[1], 10);
        loadParentAttendance();
      }
    });
  }

  if (prevBtn) prevBtn.addEventListener('click', () => shiftParentMonth(-1));
  if (nextBtn) nextBtn.addEventListener('click', () => shiftParentMonth(1));

  // Start auto-refresh for real-time updates when teacher marks attendance
  startParentAutoRefresh();
}

/* ── ADMIN VIEW ── */
let adminDailyChart = null;
let adminMonthlyChart = null;
let adminReportData = [];

const adminApiBase = (typeof adminAttendanceBootstrap !== 'undefined' && adminAttendanceBootstrap.apiBase)
  ? adminAttendanceBootstrap.apiBase
  : (typeof attendanceApiBase !== 'undefined' ? attendanceApiBase : '../Controller/AttendanceDataController.php');

function destroyAdminCharts() {
  if (adminDailyChart) {
    adminDailyChart.destroy();
    adminDailyChart = null;
  }
  if (adminMonthlyChart) {
    adminMonthlyChart.destroy();
    adminMonthlyChart = null;
  }
}

function renderAdminCharts(dailyData, monthlyData) {
  const dailyCanvas = document.getElementById('daily-chart');
  const monthlyCanvas = document.getElementById('monthly-chart');
  if (typeof Chart === 'undefined') return;

  destroyAdminCharts();

  if (dailyCanvas) {
    adminDailyChart = new Chart(dailyCanvas, {
      type: 'bar',
      data: {
        labels: (dailyData || []).map(d => d.date),
        datasets: [
          { label: 'Present', data: (dailyData || []).map(d => d.present), backgroundColor: '#10B981', borderRadius: 4 },
          { label: 'Absent', data: (dailyData || []).map(d => d.absent), backgroundColor: '#EF4444', borderRadius: 4 },
          { label: 'Late', data: (dailyData || []).map(d => d.late), backgroundColor: '#F59E0B', borderRadius: 4 },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } } },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9CA3AF' } },
          y: { grid: { color: '#F3F4F6' }, ticks: { font: { size: 10 }, color: '#9CA3AF' }, beginAtZero: true },
        },
      },
    });
  }

  if (monthlyCanvas) {
    const rates = (monthlyData || []).map(d => d.rate);
    const minY = rates.length ? Math.max(0, Math.min(...rates) - 5) : 85;

    adminMonthlyChart = new Chart(monthlyCanvas, {
      type: 'line',
      data: {
        labels: (monthlyData || []).map(d => d.month),
        datasets: [{
          label: 'Rate',
          data: rates,
          borderColor: '#E91E8C',
          backgroundColor: 'transparent',
          borderWidth: 3,
          pointBackgroundColor: '#E91E8C',
          pointRadius: 5,
          tension: 0.4,
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { labels: { usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } },
          tooltip: { callbacks: { label: ctx => ctx.parsed.y + '%' } },
        },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9CA3AF' } },
          y: {
            min: minY,
            max: 100,
            grid: { color: '#F3F4F6' },
            ticks: { font: { size: 11 }, color: '#9CA3AF', callback: v => v + '%' },
          },
        },
      },
    });
  }
}

function applyAdminOverview(data) {
  if (!data) return;

  const summary = data.summary || {};
  const schoolRate = document.getElementById('admin-school-rate');
  const presentEl = document.getElementById('admin-present-today');
  const absentEl = document.getElementById('admin-absent-today');
  const belowEl = document.getElementById('admin-below-85');
  const reportDate = document.getElementById('admin-report-date');
  const dailyTitle = document.getElementById('admin-daily-chart-title');

  if (schoolRate) schoolRate.textContent = (summary.schoolWideRate ?? 0) + '%';
  if (presentEl) presentEl.textContent = summary.presentToday ?? 0;
  if (absentEl) absentEl.textContent = summary.absentToday ?? 0;
  if (belowEl) belowEl.textContent = summary.classesBelow85 ?? 0;
  if (reportDate) reportDate.textContent = data.reportDateLabel || '';
  if (dailyTitle) dailyTitle.textContent = 'Daily Attendance (' + (data.chartMonthLabel || '') + ')';

  adminReportData = data.courseReports || [];
  renderAdminReportsTable(adminReportData);
  renderAdminCharts(data.dailyChart || [], data.monthlyChart || []);
}

function renderAdminReportsTable(reports) {
  const tbody = document.getElementById('admin-reports-body');
  if (!tbody) return;

  if (!reports.length) {
    tbody.innerHTML = '<tr><td colspan="7" class="empty-list-msg">No course reports for this date.</td></tr>';
    return;
  }

  tbody.innerHTML = reports.map(r => `
    <tr>
      <td class="td-bold">${escapeHtml(r.courseName)}</td>
      <td class="td-muted">${escapeHtml(r.teacherName)}</td>
      <td class="td-bold">${r.totalStudents}</td>
      <td><span class="green">${r.present}</span></td>
      <td><span class="red">${r.absent}</span></td>
      <td><span class="amber">${r.late}</span></td>
      <td><span class="rate-badge ${escapeHtml(r.rateClass)}">${r.rate}%</span></td>
    </tr>
  `).join('');
}

async function fetchAdminOverview(courseId, date) {
  const url = `${adminApiBase}?action=overview&courseId=${encodeURIComponent(courseId)}&date=${encodeURIComponent(date)}`;
  const response = await fetch(url, {
    method: 'GET',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
  });
  const result = await response.json();
  if (!response.ok || !result.success) {
    throw new Error(result.error || 'Failed to load admin data');
  }
  return result.data;
}

function exportAdminCsv() {
  if (!adminReportData.length) {
    alert('No report data to export.');
    return;
  }

  const headers = ['Course', 'Teacher', 'Enrolled', 'Present', 'Absent', 'Late', 'Rate'];
  const rows = adminReportData.map(r => [
    r.courseName,
    r.teacherName,
    r.totalStudents,
    r.present,
    r.absent,
    r.late,
    r.rate + '%',
  ]);

  const csv = [headers, ...rows]
    .map(row => row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(','))
    .join('\n');

  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'attendance-report-' + (document.getElementById('admin-date-filter')?.value || 'export') + '.csv';
  link.click();
  URL.revokeObjectURL(link.href);
}

function initAdminView() {
  if (typeof adminAttendanceBootstrap === 'undefined') return;

  applyAdminOverview(adminAttendanceBootstrap);

  const applyBtn = document.getElementById('admin-apply-filters');
  const exportBtn = document.getElementById('admin-export-csv');
  const courseSelect = document.getElementById('admin-course-select');
  const dateInput = document.getElementById('admin-date-filter');

  async function applyFilters() {
    const courseId = parseInt(courseSelect?.value || '0', 10) || 0;
    const date = dateInput?.value || new Date().toISOString().split('T')[0];

    if (applyBtn) applyBtn.disabled = true;
    try {
      const data = await fetchAdminOverview(courseId, date);
      applyAdminOverview(data);
    } catch (err) {
      console.error(err);
      alert(err.message || 'Could not load reports.');
    } finally {
      if (applyBtn) applyBtn.disabled = false;
    }
  }

  if (applyBtn) applyBtn.addEventListener('click', applyFilters);
  if (exportBtn) exportBtn.addEventListener('click', exportAdminCsv);
}

document.addEventListener('DOMContentLoaded', () => {
  if (activeRole === 'teacher') {
    initTeacherView();
  } else if (activeRole === 'parent') {
    initParentView();
  } else if (activeRole === 'admin') {
    initAdminView();
  }
});
