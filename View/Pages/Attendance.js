/* ── DATA ── */
const students = [
  { id: 1, name: 'Emma Johnson',   emoji: '👧', cls: 'KG1 – Sunflower' },
  { id: 2, name: 'Noah Williams',  emoji: '👦', cls: 'KG1 – Sunflower' },
  { id: 3, name: 'Sophia Brown',   emoji: '👧', cls: 'KG1 – Sunflower' },
  { id: 4, name: 'Liam Davis',     emoji: '👦', cls: 'KG1 – Sunflower' },
  { id: 5, name: 'Olivia Miller',  emoji: '👧', cls: 'KG1 – Sunflower' },
  { id: 6, name: 'Mason Wilson',   emoji: '👦', cls: 'KG1 – Sunflower' },
  { id: 7, name: 'Ava Moore',      emoji: '👧', cls: 'KG1 – Sunflower' },
  { id: 8, name: 'Elijah Taylor',  emoji: '👦', cls: 'KG1 – Sunflower' },
];

const calData = [
  { date:1,status:'present'},{date:2,status:'present'},{date:3,status:'weekend'},
  {date:4,status:'weekend'},{date:5,status:'present'},{date:6,status:'absent'},
  {date:7,status:'present'},{date:8,status:'present'},{date:9,status:'late'},
  {date:10,status:'weekend'},{date:11,status:'weekend'},{date:12,status:'present'},
  {date:13,status:'present'},{date:14,status:'present'},{date:15,status:'present'},
  {date:16,status:'absent'},{date:17,status:'present'},{date:18,status:'weekend'},
  {date:19,status:'weekend'},{date:20,status:'present'},{date:21,status:'present'},
  {date:22,status:'late'},{date:23,status:'present'},{date:24,status:'present'},
  {date:25,status:'weekend'},{date:26,status:'weekend'},{date:27,status:'present'},
  {date:28,status:'present'},{date:29,status:'present'},{date:30,status:'present'},
];

/* ── STATE ── */
let attendance = {}; // { studentId: 'present'|'absent'|'late'|null }

/* ── VIEW SWITCHING ── */
document.querySelectorAll('.view-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const view = btn.dataset.view;
    document.querySelectorAll('.view-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('view-' + view).classList.add('active');
  });
});

/* ── TEACHER VIEW ── */
function renderStudentList() {
  const list = document.getElementById('student-list');
  list.innerHTML = '';
  students.forEach(s => {
    const status = attendance[s.id] || null;
    const row = document.createElement('div');
    row.className = 'student-row';
    row.innerHTML = `
      <span class="student-emoji">${s.emoji}</span>
      <div class="student-info">
        <div class="student-name">${s.name}</div>
        <div class="student-class">${s.cls}</div>
      </div>
      <div class="student-btns">
        <button class="status-btn ${status === 'present' ? 'btn-present-on' : 'btn-present-off'}" data-id="${s.id}" data-status="present">✓ Present</button>
        <button class="status-btn ${status === 'absent'  ? 'btn-absent-on'  : 'btn-absent-off'}"  data-id="${s.id}" data-status="absent">✗ Absent</button>
        <button class="status-btn ${status === 'late'    ? 'btn-late-on'    : 'btn-late-off'}"    data-id="${s.id}" data-status="late">⏰ Late</button>
      </div>
    `;
    list.appendChild(row);
  });

  // Attach button listeners
  list.querySelectorAll('.status-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = parseInt(btn.dataset.id);
      const st = btn.dataset.status;
      attendance[id] = attendance[id] === st ? null : st;
      updateStats();
      renderStudentList();
    });
  });
}

function updateStats() {
  const vals = Object.values(attendance);
  const present = vals.filter(v => v === 'present').length;
  const absent  = vals.filter(v => v === 'absent').length;
  const late    = vals.filter(v => v === 'late').length;
  const marked  = vals.filter(Boolean).length;

  document.getElementById('stat-present').textContent = present;
  document.getElementById('stat-absent').textContent  = absent;
  document.getElementById('stat-late').textContent    = late;
  document.getElementById('marked-count').textContent = `${marked}/${students.length} marked`;
  document.getElementById('footer-count').textContent = `${marked}/${students.length} students marked`;
  document.getElementById('submit-btn').disabled = marked === 0;
}

// Class select label
document.getElementById('teacher-class-select').addEventListener('change', e => {
  const cls = e.target.value.replace(/^[^\w]+ /, '');
  document.getElementById('class-header-title').textContent = 'Students – ' + cls;
});

// Set today's date
document.getElementById('teacher-date').value = new Date().toISOString().split('T')[0];

// Mark all present
document.getElementById('mark-all-present').addEventListener('click', () => {
  students.forEach(s => { attendance[s.id] = 'present'; });
  updateStats();
  renderStudentList();
});

// Submit
document.getElementById('submit-btn').addEventListener('click', () => {
  const feedback = document.getElementById('submit-feedback');
  const counter  = document.getElementById('footer-count');
  feedback.classList.remove('hidden');
  counter.classList.add('hidden');
  setTimeout(() => {
    feedback.classList.add('hidden');
    counter.classList.remove('hidden');
  }, 3000);
});

/* ── PARENT VIEW CALENDAR ── */
function renderCalendar() {
  const grid = document.getElementById('calendar-grid');
  const present = calData.filter(d => d.status === 'present').length;
  const absent  = calData.filter(d => d.status === 'absent').length;
  const late    = calData.filter(d => d.status === 'late').length;
  const school  = calData.filter(d => d.status !== 'weekend').length;
  const rate    = Math.round((present / school) * 100);

  document.getElementById('parent-present').textContent = present;
  document.getElementById('parent-absent').textContent  = absent;
  document.getElementById('parent-late').textContent    = late;
  document.getElementById('parent-rate-value').textContent = rate + '%';
  document.getElementById('parent-rate-value').className = 'child-rate-value ' + (rate >= 90 ? 'green' : 'amber');

  const notice = document.getElementById('attendance-notice');
  if (rate >= 90) { notice.style.display = 'none'; }

  grid.innerHTML = '';
  calData.forEach(d => {
    const cell = document.createElement('div');
    cell.className = 'cal-cell cal-' + d.status;
    cell.title = d.status.charAt(0).toUpperCase() + d.status.slice(1);
    cell.textContent = d.date;
    grid.appendChild(cell);
  });
}

/* ── ADMIN CHARTS ── */
function renderCharts() {
  const dailyData = [
    { date:'Dec 1',  present:230, absent:12, late:6 },
    { date:'Dec 2',  present:228, absent:14, late:6 },
    { date:'Dec 3',  present:235, absent:8,  late:5 },
    { date:'Dec 4',  present:222, absent:18, late:8 },
    { date:'Dec 5',  present:238, absent:6,  late:4 },
    { date:'Dec 8',  present:225, absent:15, late:8 },
    { date:'Dec 9',  present:232, absent:10, late:6 },
    { date:'Dec 10', present:234, absent:8,  late:6 },
  ];
  const monthlyData = [
    { month:'Aug', rate:91 },{ month:'Sep', rate:93 },{ month:'Oct', rate:94 },
    { month:'Nov', rate:92 },{ month:'Dec', rate:94 },
  ];

  new Chart(document.getElementById('daily-chart'), {
    type: 'bar',
    data: {
      labels: dailyData.map(d => d.date),
      datasets: [
        { label:'Present', data: dailyData.map(d => d.present), backgroundColor:'#10B981', borderRadius:4 },
        { label:'Absent',  data: dailyData.map(d => d.absent),  backgroundColor:'#EF4444', borderRadius:4 },
        { label:'Late',    data: dailyData.map(d => d.late),    backgroundColor:'#F59E0B', borderRadius:4 },
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { usePointStyle:true, pointStyle:'circle', font:{size:11} } } },
      scales: {
        x: { grid:{ display:false }, ticks:{ font:{size:10}, color:'#9CA3AF' } },
        y: { grid:{ color:'#F3F4F6' }, ticks:{ font:{size:10}, color:'#9CA3AF' } }
      }
    }
  });

  new Chart(document.getElementById('monthly-chart'), {
    type: 'line',
    data: {
      labels: monthlyData.map(d => d.month),
      datasets: [{
        label:'Rate',
        data: monthlyData.map(d => d.rate),
        borderColor:'#E91E8C',
        backgroundColor:'transparent',
        borderWidth:3,
        pointBackgroundColor:'#E91E8C',
        pointRadius:5,
        tension:0.4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { labels: { usePointStyle:true, pointStyle:'circle', font:{size:11} } },
        tooltip: { callbacks: { label: ctx => ctx.parsed.y + '%' } }
      },
      scales: {
        x: { grid:{ display:false }, ticks:{ font:{size:11}, color:'#9CA3AF' } },
        y: { min:85, max:100, grid:{ color:'#F3F4F6' }, ticks:{ font:{size:11}, color:'#9CA3AF', callback: v => v + '%' } }
      }
    }
  });
}

/* ── INIT ── */
renderStudentList();
renderCalendar();
renderCharts();
