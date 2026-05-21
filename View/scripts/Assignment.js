	/* ─── DATA ─── */
const subjectColors = {
  'Literacy':       { color: '#E91E8C', bg: '#FFF0F7', emoji: '📖' },
  'Math':           { color: '#1565C0', bg: '#EFF6FF', emoji: '🔢' },
  'Creative Arts':  { color: '#10B981', bg: '#F0FDF4', emoji: '🎨' },
  'Science':        { color: '#8B5CF6', bg: '#F5F3FF', emoji: '🔬' },
  'Social Studies': { color: '#F59E0B', bg: '#FFFBEB', emoji: '🌍' },
  'Music':          { color: '#EF4444', bg: '#FEF2F2', emoji: '🎵' },
};

const statusConfig = {
  pending:   { label: 'Pending',   color: '#F59E0B', bg: '#FFFBEB', icon: '⏳' },
  submitted: { label: 'Submitted', color: '#1565C0', bg: '#EFF6FF', icon: '📤' },
  graded:    { label: 'Graded',    color: '#10B981', bg: '#F0FDF4', icon: '✅' },
  overdue:   { label: 'Overdue',   color: '#EF4444', bg: '#FEF2F2', icon: '⚠️' },
};

const assignments = [
  {
    id: 1, title: 'My Family Drawing', subject: 'Creative Arts',
    cls: 'KG1 – Sunflower', dueDate: 'Dec 15, 2025', daysLeft: 5,
    description: 'Draw a picture of your family and label each person with their name. Use crayons or colored pencils. Be as creative as you like!',
    emoji: '🎨', totalStudents: 22, submitted: 18, graded: 12,
    status: 'submitted', grade: 'A',
    feedback: 'Beautiful drawing! Emma did a wonderful job labeling everyone.',
    attachments: ['family_drawing.jpg'],
    color: '#10B981', bg: '#F0FDF4',
  },
  {
    id: 2, title: 'Number Tracing Worksheet', subject: 'Math',
    cls: 'KG1 – Sunflower', dueDate: 'Dec 13, 2025', daysLeft: 3,
    description: 'Trace the numbers 1–20 on the provided worksheet. Then count and draw the matching number of stars for each number.',
    emoji: '🔢', totalStudents: 22, submitted: 15, graded: 8,
    status: 'pending',
    color: '#1565C0', bg: '#EFF6FF',
  },
  {
    id: 3, title: 'Letter Recognition – A to E', subject: 'Literacy',
    cls: 'KG1 – Sunflower', dueDate: 'Dec 12, 2025', daysLeft: 2,
    description: 'Circle all the letters A, B, C, D, and E in the magazine cutout pages. Then write each letter 5 times on your lined paper.',
    emoji: '📖', totalStudents: 22, submitted: 20, graded: 20,
    status: 'graded', grade: 'B+',
    feedback: 'Good work! Pay extra attention to the letter D formation.',
    color: '#E91E8C', bg: '#FFF0F7',
  },
  {
    id: 4, title: 'Seasons Collage', subject: 'Science',
    cls: 'KG1 – Sunflower', dueDate: 'Dec 10, 2025', daysLeft: -1,
    description: 'Create a collage showing your favorite season using magazine images, colored paper, and drawings. Write one sentence about why you love that season.',
    emoji: '🔬', totalStudents: 22, submitted: 19, graded: 17,
    status: 'overdue',
    color: '#8B5CF6', bg: '#F5F3FF',
  },
  {
    id: 5, title: 'Nursery Rhyme Practice', subject: 'Music',
    cls: 'KG1 – Sunflower', dueDate: 'Dec 18, 2025', daysLeft: 8,
    description: 'Practise "Twinkle Twinkle Little Star" and "Humpty Dumpty" at home. Ask your parent to record a short video of you singing. Upload the video or bring it on a USB.',
    emoji: '🎵', totalStudents: 22, submitted: 5, graded: 0,
    status: 'pending',
    color: '#EF4444', bg: '#FEF2F2',
  },
  {
    id: 6, title: 'My Community Helpers', subject: 'Social Studies',
    cls: 'KG1 – Sunflower', dueDate: 'Dec 20, 2025', daysLeft: 10,
    description: 'Draw or cut pictures of 3 community helpers (e.g., doctor, firefighter, teacher). Glue them on your poster and write their name below each picture.',
    emoji: '🌍', totalStudents: 22, submitted: 2, graded: 0,
    status: 'pending',
    color: '#F59E0B', bg: '#FFFBEB',
  },
];

/* ─── STATE ─── */
let activeRole = 'teacher'; // Will be overridden by PHP-provided currentUserRole
let selectedAssignment = null;
let showCreateModal = false;
let teacherFilter = 'all';
let teacherSearch = '';
let assignmentsData = []; // Will be fetched from database
let coursesData = []; // Will be fetched from database

/* ─── SVG ICONS (inline) ─── */
const icons = {
  plus: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>`,
  x: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
  search: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>`,
  chevronDown: `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>`,
  eye: `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`,
  edit: `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`,
  send: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>`,
  check: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`,
  paperclip: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>`,
  image: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>`,
  fileText: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>`,
  alert: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
  graduation: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>`,
  heart: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`,
  baby: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12h.01M15 12h.01M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5"/><path d="M12 2a5 5 0 0 1 5 5c0 .7-.1 1.3-.3 1.9A5 5 0 0 1 12 22a5 5 0 0 1-5-5V9a5 5 0 0 1 5-5z"/></svg>`,
  loader: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="animation: spin 1s linear infinite"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>`,
};

/* ─── HELPERS ─── */
function pct(v, t) { return t > 0 ? Math.round((v / t) * 100) : 0; }

/**
 * Get color scheme for a subject/course name
 */
function getSubjectColor(subjectName) {
  // Check if it exists in hardcoded colors first
  if (subjectColors[subjectName]) {
    return subjectColors[subjectName];
  }

  // Generate a color based on course name hash
  const colors = [
    { color: '#E91E8C', bg: '#FFF0F7', emoji: '📚' },
    { color: '#1565C0', bg: '#EFF6FF', emoji: '📖' },
    { color: '#10B981', bg: '#F0FDF4', emoji: '🎨' },
    { color: '#8B5CF6', bg: '#F5F3FF', emoji: '🎓' },
    { color: '#F59E0B', bg: '#FFFBEB', emoji: '🔬' },
    { color: '#EF4444', bg: '#FEF2F2', emoji: '🎵' },
  ];

  // Use name length as hash to pick a color consistently
  const index = subjectName.length % colors.length;
  return colors[index];
}

function dueBadgeHTML(a, sc) {
  if (a.daysLeft < 0)
    return `<span class="due-badge" style="background:#FEF2F2;color:#EF4444">⚠️ Overdue</span>`;
  if (a.daysLeft <= 3)
    return `<span class="due-badge" style="background:#FFFBEB;color:#F59E0B">⏰ ${a.daysLeft}d left</span>`;
  return `<span class="due-badge" style="background:#F0FDF4;color:#10B981">📅 ${a.daysLeft}d left</span>`;
}

function progressBarHTML(label, value, total, color) {
  const w = pct(value, total);
  return `
    <div class="progress-row">
      <div class="progress-label-row">
        <span>${label}</span>
        <span style="color:${color}">${value}/${total}</span>
      </div>
      <div class="progress-track">
        <div class="progress-fill" style="width:${w}%;background:${color}"></div>
      </div>
    </div>`;
}

/* ─── RENDER TEACHER VIEW ─── */
function renderTeacherView() {
  const filtered = assignmentsData.filter(a =>
    (teacherFilter === 'all' || a.subject === teacherFilter) &&
    (teacherSearch === '' || a.title.toLowerCase().includes(teacherSearch.toLowerCase()))
  );

  const stats = [
    { label: 'Total Assignments', value: assignmentsData.length, icon: '📚', color: '#1565C0', bg: '#EFF6FF' },
    { label: 'Pending Review', value: assignmentsData.filter(a => a.submitted > a.graded).length, icon: '📋', color: '#F59E0B', bg: '#FFFBEB' },
    { label: 'Fully Graded', value: assignmentsData.filter(a => a.graded === a.submitted && a.submitted > 0).length, icon: '✅', color: '#10B981', bg: '#F0FDF4' },
    { label: 'Overdue Items', value: assignmentsData.filter(a => a.daysLeft < 0).length, icon: '⚠️', color: '#EF4444', bg: '#FEF2F2' },
  ];

  const subjectOptions = coursesData.map(c =>
    `<option value="${c.name}" ${teacherFilter === c.name ? 'selected' : ''}>${c.name}</option>`
  ).join('');

  const cardsHTML = filtered.length === 0
    ? `<div class="empty-state" style="grid-column:1/-1">
        <div class="empty-icon">📭</div>
        <div class="empty-title">No assignments found</div>
        <div class="empty-sub">Try adjusting your search or filters</div>
      </div>`
    : filtered.map((a, i) => {
        const sc = getSubjectColor(a.subject);
        const submittedPct = pct(a.submitted, a.totalStudents);
        const gradedPct = a.submitted > 0 ? pct(a.graded, a.submitted) : 0;
        return `
          <div class="assignment-card" style="animation-delay:${i * 0.06}s" data-id="${a.id}">
            <div class="card-color-strip" style="background:${sc.color}"></div>
            <div class="card-body">
              <div class="card-header">
                <div>
                  <span class="card-emoji">${a.emoji || sc.emoji}</span>
                  <span class="subject-pill" style="background:${sc.color}">${a.subject}</span>
                </div>
                ${dueBadgeHTML(a, sc)}
              </div>
              <h4 class="card-title">${a.title}</h4>
              <p class="card-meta">${a.cls} · Due ${a.dueDate}</p>
              <div class="progress-section">
                ${progressBarHTML('Submitted', a.submitted, a.totalStudents, '#1565C0')}
                ${progressBarHTML('Graded', a.graded, a.submitted, '#10B981')}
              </div>
              <div class="card-actions">
                <button class="card-btn btn-view" style="background:${sc.bg};color:${sc.color}" onclick="openDetail(${a.id}, event)">${icons.eye} View</button>
                <button class="card-btn btn-edit" onclick="openEdit(${a.id}, event)">${icons.edit} Edit</button>
              </div>
            </div>
          </div>`;
      }).join('');

  return `
    <div class="view-panel">
      <div class="stats-grid">
        ${stats.map((s, i) => `
          <div class="stat-card" style="background:${s.bg};animation-delay:${i * 0.07}s">
            <div class="stat-icon">${s.icon}</div>
            <div>
              <div class="stat-value" style="color:${s.color}">${s.value}</div>
              <div class="stat-label">${s.label}</div>
            </div>
          </div>`).join('')}
      </div>

      <div class="controls-bar">
        <div class="search-wrap">
          ${icons.search}
          <input id="teacher-search" type="text" placeholder="Search assignments…" value="${teacherSearch}" oninput="onSearchInput(this.value)">
        </div>
        <div class="select-wrap">
          <select id="subject-filter" onchange="onFilterChange(this.value)">
            <option value="all" ${teacherFilter === 'all' ? 'selected' : ''}>All Subjects</option>
            ${subjectOptions}
          </select>
          <span class="chevron">${icons.chevronDown}</span>
        </div>
        <button class="btn-new-assignment" onclick="openCreate()">
          ${icons.plus} New Assignment
        </button>
      </div>

      <div class="assignments-grid">
        ${cardsHTML}
      </div>
    </div>`;
}

/* ─── RENDER PARENT VIEW ─── */
function renderParentView() {
  const pending = assignmentsData.filter(a => a.status === 'pending');
  const graded = assignmentsData.filter(a => a.status === 'graded');
  const overdue = assignmentsData.filter(a => a.status === 'overdue');

  const miniStats = [
    { label: 'Total', value: assignmentsData.length, color: '#1565C0', bg: '#EFF6FF' },
    { label: 'Pending', value: pending.length, color: '#F59E0B', bg: '#FFFBEB' },
    { label: 'Graded', value: graded.length, color: '#10B981', bg: '#F0FDF4' },
    { label: 'Overdue', value: overdue.length, color: '#EF4444', bg: '#FEF2F2' },
  ];

  const overdueAlert = overdue.length > 0 ? `
    <div class="overdue-alert">
      ${icons.alert}
      <div>
        <div class="overdue-alert-title">⚠️ ${overdue.length} Overdue Assignment${overdue.length > 1 ? 's' : ''}</div>
        <p class="overdue-alert-sub">Please help Emma complete the overdue work as soon as possible. Contact the teacher if you need an extension.</p>
      </div>
    </div>` : '';

  const listItems = assignmentsData.map((a, i) => {
    const sc = getSubjectColor(a.subject);
    const st = statusConfig[a.status];
    return `
      <div class="parent-list-item" style="border-color:${sc.color};animation-delay:${i * 0.05}s" onclick="openDetail(${a.id})">
        <span class="parent-list-emoji">${a.emoji}</span>
        <div class="parent-list-info">
          <div class="flex items-center gap-2 flex-wrap">
            <div class="parent-list-title">${a.title}</div>
            <span class="subject-pill" style="background:${sc.color};font-size:0.68rem;padding:0.15rem 0.55rem">${a.subject}</span>
          </div>
          <div class="parent-list-meta">Due: ${a.dueDate} · ${a.subject}</div>
          ${a.feedback ? `<div class="parent-list-feedback">💬 "${a.feedback.slice(0, 50)}…"</div>` : ''}
        </div>
        ${st ? `
          <div class="parent-list-badge">
            <span class="status-pill" style="background:${st.bg};color:${st.color}">${st.icon} ${st.label}</span>
            ${a.grade ? `<span class="grade-text" style="color:${st.color}">${a.grade}</span>` : ''}
          </div>` : ''}
      </div>`;
  }).join('');

  return `
    <div class="view-panel">
      <div class="parent-summary-card">
        <div class="parent-child-info">
          <span class="parent-child-avatar">👧</span>
          <div>
            <div class="parent-child-name">Emma Johnson</div>
            <div class="parent-child-class">KG1 – Sunflower · Ms. Emily Watson</div>
          </div>
        </div>
        <div class="parent-mini-stats ml-auto">
          ${miniStats.map(s => `
            <div class="parent-mini-stat" style="background:${s.bg}">
              <div class="v" style="color:${s.color}">${s.value}</div>
              <div class="l">${s.label}</div>
            </div>`).join('')}
        </div>
      </div>
      ${overdueAlert}
      <div class="parent-list">${listItems}</div>
    </div>`;
}

/* ─── RENDER CHILD VIEW ─── */
function renderChildView() {
  const pending = assignmentsData.filter(a => a.status === 'pending' || a.status === 'overdue');
  const done = assignmentsData.filter(a => a.status === 'submitted' || a.status === 'graded');

  const childStats = [
    { icon: '📚', label: 'Total Tasks', value: assignmentsData.length, color: '#1565C0', bg: '#EFF6FF' },
    { icon: '⏳', label: 'To Do', value: pending.length, color: '#F59E0B', bg: '#FFFBEB' },
    { icon: '✅', label: 'Done', value: done.length, color: '#10B981', bg: '#F0FDF4' },
    { icon: '🏆', label: 'Gold Stars', value: 3, color: '#E91E8C', bg: '#FFF0F7' },
  ];

  const todoCards = pending.map((a, i) => {
    const sc = getSubjectColor(a.subject);
    const badge = a.status === 'overdue'
      ? `<span class="child-overdue-badge">⚠️ Overdue!</span>`
      : `<span class="child-days-badge" style="background:${sc.bg};color:${sc.color}">⏰ ${a.daysLeft}d left</span>`;
    return `
      <div class="child-todo-card" style="border-color:${a.status === 'overdue' ? '#FECACA' : sc.color + '40'};animation-delay:${i * 0.07}s" onclick="openDetail(${a.id})">
        <div class="child-todo-header">
          <span class="child-todo-emoji">${a.emoji}</span>
          ${badge}
        </div>
        <h4 class="child-todo-title">${a.title}</h4>
        <p class="child-todo-meta">Due ${a.dueDate} · ${a.subject}</p>
        <button class="btn-start" style="background:linear-gradient(135deg,${sc.color},${sc.color}CC)" onclick="event.stopPropagation();openDetail(${a.id})">🚀 Start Assignment</button>
      </div>`;
  }).join('');

  const doneItems = done.map((a, i) => {
    const sc = getSubjectColor(a.subject);
    return `
      <div class="child-done-item" style="animation-delay:${i * 0.06}s" onclick="openDetail(${a.id})">
        <span class="child-done-emoji">${a.emoji}</span>
        <div class="child-done-info">
          <div class="child-done-title">${a.title}</div>
          <div class="child-done-meta">${a.subject} · ${a.cls}</div>
          ${a.feedback ? `<div class="child-done-feedback">"${a.feedback.slice(0, 45)}…"</div>` : ''}
        </div>
        ${a.grade
          ? `<div class="child-done-grade">${a.grade.charAt(0)}</div>`
          : `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
        }
      </div>`;
  }).join('');

  return `
    <div class="view-panel">
      <div class="child-welcome">
        <div>
          <div class="child-welcome-title">Hi Emma! 👋</div>
          <p class="child-welcome-sub">You have <strong>${pending.length}</strong> assignments to complete. Let's get started!</p>
          <span class="child-welcome-tag">🏆 3 Gold Stars this week</span>
        </div>
        <span class="child-star-big">🌟</span>
      </div>

      <div class="child-stats-grid">
        ${childStats.map(s => `
          <div class="child-stat-card" style="background:${s.bg}">
            <div class="stat-icon">${s.icon}</div>
            <div class="stat-value" style="color:${s.color}">${s.value}</div>
            <div class="stat-label">${s.label}</div>
          </div>`).join('')}
      </div>

      ${pending.length > 0 ? `
        <div>
          <h4 class="section-heading"><span style="color:#E91E8C">📋</span> My Assignments to Do</h4>
          <div class="child-todo-grid">${todoCards}</div>
        </div>` : ''}

      ${done.length > 0 ? `
        <div>
          <h4 class="section-heading"><span style="color:#10B981">🏆</span> Completed Assignments</h4>
          <div class="child-done-list">${doneItems}</div>
        </div>` : ''}
    </div>`;
}

/* ─── DETAIL MODAL ─── */
function renderDetailModal(a) {
  const sc = getSubjectColor(a.subject);
  const st = statusConfig[a.status];
  const daysText = a.daysLeft >= 0
    ? `<div class="modal-info-sub">${a.daysLeft} days left</div>`
    : `<div class="modal-info-sub" style="color:#f87171">Overdue by ${Math.abs(a.daysLeft)} day(s)</div>`;

  const teacherProgress = activeRole === 'teacher' ? `
    <div>
      <div class="modal-section-label">📊 Submission Progress</div>
      ${progressBarHTML('Submitted', a.submitted, a.totalStudents, '#1565C0')}
      <div style="margin-top:0.5rem"></div>
      ${progressBarHTML('Graded', a.graded, a.submitted, '#10B981')}
    </div>` : '';

  const submitSection = (activeRole === 'parent' || activeRole === 'child') && a.status !== 'graded' ? `
    <div>
      <div class="modal-section-label">📤 Submit Work</div>
      <div class="upload-drop">
        <div class="upload-icons">
          <div class="upload-icon-btn">
            <div class="upload-icon-circle" style="background:#FFF0F7;color:#E91E8C">${icons.image}</div>
            <span class="upload-icon-label">Photo</span>
          </div>
          <div class="upload-icon-btn">
            <div class="upload-icon-circle" style="background:#EFF6FF;color:#1565C0">${icons.fileText}</div>
            <span class="upload-icon-label">PDF</span>
          </div>
          <div class="upload-icon-btn">
            <div class="upload-icon-circle" style="background:#F0FDF4;color:#10B981">${icons.paperclip}</div>
            <span class="upload-icon-label">File</span>
          </div>
        </div>
        <span class="upload-hint">Click to upload or take a photo</span>
      </div>
      <textarea id="submission-note" class="modal-textarea" rows="2" placeholder="Add a note for the teacher (optional)…" style="margin-top:0.75rem"></textarea>
      <button id="submit-btn" class="btn-submit" style="background:linear-gradient(135deg,#E91E8C,#C2185B);margin-top:0.75rem" onclick="submitAssignment(this)">
        ${icons.send} Submit Assignment
      </button>
    </div>` : '';

  return `
    <div class="modal-overlay" id="detail-modal" onclick="closeOnBackdrop(event,'detail-modal')">
      <div class="modal-box">
        <div class="modal-header" style="background:linear-gradient(135deg,${sc.bg},white)">
          <div class="modal-header-info">
            <span class="modal-header-emoji">${a.emoji}</span>
            <div>
              <div class="modal-title">${a.title}</div>
              <div class="modal-tags">
                <span class="modal-subject-pill" style="background:${sc.color}">${a.subject}</span>
                <span class="modal-class-text">${a.cls}</span>
              </div>
            </div>
          </div>
          <button class="btn-close" onclick="closeDetail()">${icons.x}</button>
        </div>

        <div class="modal-body">
          <div class="modal-info-grid">
            <div class="modal-info-cell" style="background:#FFF0F7">
              <div class="modal-info-label">Due Date</div>
              <div class="modal-info-value" style="color:#E91E8C">📅 ${a.dueDate}</div>
              ${daysText}
            </div>
            ${st ? `
              <div class="modal-info-cell" style="background:${st.bg}">
                <div class="modal-info-label">Status</div>
                <div class="modal-info-value" style="color:${st.color}">${st.icon} ${st.label}</div>
                ${a.grade ? `<div class="modal-info-sub" style="color:${st.color}">Grade: ${a.grade}</div>` : ''}
              </div>` : ''}
          </div>

          <div>
            <div class="modal-section-label">📝 Instructions</div>
            <div class="modal-description">${a.description}</div>
          </div>

          ${a.wordwallCode ? `
            <div class="wordwall-container" id="wordwall-embed-${a.id}">
              <div class="modal-section-label">🎮 Interactive Activity</div>
              <div class="wordwall-embed-box"></div>
            </div>` : ''}

          ${a.feedback ? `
            <div class="modal-feedback-box">
              <div class="modal-feedback-heading">💬 Teacher Feedback</div>
              <p class="modal-feedback-text">"${a.feedback}"</p>
            </div>` : ''}

          ${teacherProgress}
          ${submitSection}
        </div>
      </div>
    </div>`;
}

/* ─── CREATE MODAL ─── */
function renderCreateModal() {
  const courseOptions = coursesData.map(c =>
    `<option value="${c.id}">${c.name}</option>`
  ).join('');

  return `
    <div class="modal-overlay" id="create-modal" onclick="closeOnBackdrop(event,'create-modal')">
      <div class="modal-box">
        <div class="create-modal-header">
          <div>
            <div class="create-modal-title">✏️ New Assignment</div>
            <div class="create-modal-sub">Create a new assignment for your class</div>
          </div>
          <button class="btn-close" onclick="closeCreate()">${icons.x}</button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Assignment Title *</label>
            <input class="form-input" id="form-title" type="text" placeholder="e.g., My Family Drawing" oninput="toggleHasValue(this)">
          </div>

          <div class="form-two-col">
            <div class="form-group">
              <label class="form-label">Course *</label>
              <div class="select-wrap" style="width:100%">
                <select class="form-input" id="form-subject" style="width:100%;padding-right:2.2rem">
                  <option value="">Select a course</option>
                  ${courseOptions}
                </select>
                <span class="chevron">${icons.chevronDown}</span>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Class *</label>
              <div class="select-wrap" style="width:100%">
                <select class="form-input" id="form-class" style="width:100%;padding-right:2.2rem">
                  <option value="">Select class</option>
                  <option>KG1 – Sunflower</option>
                  <option>KG2 – Rainbow</option>
                  <option>Nursery – Butterfly</option>
                  <option>KG2 – Stars</option>
                </select>
                <span class="chevron">${icons.chevronDown}</span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Due Date *</label>
            <input class="form-input" id="form-due" type="date">
          </div>

          <div class="form-group">
            <label class="form-label">Description / Instructions *</label>
            <textarea class="form-input" id="form-desc" rows="4" placeholder="Explain what the child needs to do, any materials required, and how to submit…" style="resize:none"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">WordWall Embed (Optional)</label>
            <textarea class="form-input" id="form-embed" rows="3" placeholder='Paste your WordWall iframe code here' style="resize:none;font-family:monospace;font-size:0.78rem"></textarea>
            <p style="font-size:0.72rem;color:#9ca3af;margin-top:0.375rem">💡 Copy the embed code from WordWall and paste it here. It will display in parent and child views.</p>
          </div>

          <div class="attach-drop">
            ${icons.paperclip.replace('stroke="currentColor"', 'stroke="#E91E8C"')}
            <span class="attach-drop-label">Attach files (images, PDFs, worksheets)</span>
            <span class="attach-drop-hint">Click or drag to upload</span>
          </div>

          <div class="form-actions">
            <button class="btn-cancel" onclick="closeCreate()">Cancel</button>
            <button class="btn-assign" id="assign-btn" onclick="saveAssignment(this)">
              ${icons.send} Assign Now
            </button>
          </div>
        </div>
      </div>
    </div>`;
}

/* ─── EDIT MODAL ─── */
function renderEditModal(a) {
  const sc = getSubjectColor(a.subject);
  const courseOptions = coursesData.map(c =>
    `<option value="${c.id}" ${a.subject === c.name ? 'selected' : ''}>${c.name}</option>`
  ).join('');

  return `
    <div class="modal-overlay" id="edit-modal" onclick="closeOnBackdrop(event,'edit-modal')">
      <div class="modal-box">
        <div class="create-modal-header" style="background:linear-gradient(135deg,${sc.bg},#EFF6FF)">
          <div>
            <div class="create-modal-title" style="color:${sc.color}">${a.emoji} Edit Assignment</div>
            <div class="create-modal-sub">Update the details for this assignment</div>
          </div>
          <button class="btn-close" onclick="closeEdit()">${icons.x}</button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Assignment Title *</label>
            <input class="form-input has-value" id="edit-title" type="text" value="${a.title}">
          </div>

          <div class="form-two-col">
            <div class="form-group">
              <label class="form-label">Course *</label>
              <div class="select-wrap" style="width:100%">
                <select class="form-input" id="edit-subject" style="width:100%;padding-right:2.2rem">
                  <option value="">Select a course</option>
                  ${courseOptions}
                </select>
                <span class="chevron">${icons.chevronDown}</span>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Due Date *</label>
              <input class="form-input" id="edit-due" type="date" value="${a.dueDateRaw}">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Description / Instructions *</label>
            <textarea class="form-input" id="edit-desc" rows="4" style="resize:none">${a.description}</textarea>
          </div>

          <div class="form-group">
            <label class="form-label">WordWall Embed (Optional)</label>
            <textarea class="form-input" id="edit-embed" rows="3" placeholder='Paste your WordWall iframe code here' style="resize:none;font-family:monospace;font-size:0.78rem">${a.wordwallCode || ''}</textarea>
            <p style="font-size:0.72rem;color:#9ca3af;margin-top:0.375rem">💡 Copy the embed code from WordWall and paste it here.</p>
          </div>

          <div class="form-actions">
            <button class="btn-cancel" onclick="closeEdit()">Cancel</button>
            <button class="btn-assign" id="save-edit-btn" onclick="saveEdit(${a.id}, this)">
              💾 Save Changes
            </button>
          </div>
        </div>
      </div>
    </div>`;
}

/* ─── ACTION HANDLERS ─── */
function openDetail(id, e) {
  if (e) e.stopPropagation();
  selectedAssignment = assignmentsData.find(a => a.id === id);
  document.getElementById('modal-container').innerHTML = renderDetailModal(selectedAssignment);
  
  // Inject WordWall embed code after modal is rendered
  if (selectedAssignment && selectedAssignment.wordwallCode) {
    setTimeout(() => {
      const embedBox = document.querySelector(`#wordwall-embed-${id} .wordwall-embed-box`);
      if (embedBox) {
        embedBox.innerHTML = selectedAssignment.wordwallCode;
      }
    }, 0);
  }
}

function closeDetail() {
  document.getElementById('modal-container').innerHTML = '';
  selectedAssignment = null;
}

function openEdit(id, e) {
  if (e) e.stopPropagation();
  const a = assignmentsData.find(x => x.id === id);
  document.getElementById('modal-container').innerHTML = renderEditModal(a);
}

function closeEdit() {
  document.getElementById('modal-container').innerHTML = '';
}

async function saveEdit(id, btn) {
  const title = document.getElementById('edit-title').value.trim();
  const courseId = document.getElementById('edit-subject').value.trim();
  const dueDate = document.getElementById('edit-due').value.trim();
  const desc = document.getElementById('edit-desc').value.trim();
  const embedCode = document.getElementById('edit-embed').value.trim();

  // Validate required fields
  if (!title) { alert('Please enter a title.'); return; }
  if (!courseId) { alert('Please select a course.'); return; }
  if (!dueDate) { alert('Please select a due date.'); return; }
  if (!desc) { alert('Please enter description/instructions.'); return; }

  btn.disabled = true;
  btn.innerHTML = `${icons.loader} Saving...`;

  try {
    const response = await fetch('../Controller/AssignmentDataController.php?action=update', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({
        assignmentId: id,
        title: title,
        courseId: parseInt(courseId),
        dueDate: dueDate,
        description: desc,
        embedCode: embedCode,
      }),
    });

    const result = await response.json();

    if (!response.ok || !result.success) {
      alert('Error: ' + (result.error || 'Failed to update assignment'));
      btn.disabled = false;
      btn.innerHTML = `💾 Save Changes`;
      return;
    }

    btn.style.background = '#10B981';
    btn.innerHTML = `${icons.check} Saved!`;

    // Refresh assignments and close modal
    setTimeout(() => {
      fetchAssignmentsFromDB().then(() => {
        renderCurrentView();
        closeEdit();
        btn.disabled = false;
        btn.style.background = '';
        btn.innerHTML = `💾 Save Changes`;
      });
    }, 1000);
  } catch (error) {
    console.error('Error updating assignment:', error);
    alert('Network error: ' + error.message);
    btn.disabled = false;
    btn.innerHTML = `💾 Save Changes`;
  }
}

function openCreate() {
  document.getElementById('modal-container').innerHTML = renderCreateModal();
}

function closeCreate() {
  document.getElementById('modal-container').innerHTML = '';
}

async function saveAssignment(btn) {
  const title = document.getElementById('form-title').value.trim();
  const courseId = document.getElementById('form-subject').value.trim();
  const dueDate = document.getElementById('form-due').value.trim();
  const description = document.getElementById('form-desc').value.trim();
  const embedCode = document.getElementById('form-embed').value.trim();

  // Validate required fields
  if (!title) { alert('Please enter a title.'); return; }
  if (!courseId) { alert('Please select a course.'); return; }
  if (!dueDate) { alert('Please select a due date.'); return; }
  if (!description) { alert('Please enter description/instructions.'); return; }

  btn.disabled = true;
  btn.innerHTML = `${icons.loader} Creating...`;

  try {
    const response = await fetch('../Controller/AssignmentDataController.php?action=create', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({
        title: title,
        courseId: parseInt(courseId),
        dueDate: dueDate,
        description: description,
        embedCode: embedCode,
      }),
    });

    const result = await response.json();

    if (!response.ok || !result.success) {
      alert('Error: ' + (result.error || 'Failed to create assignment'));
      btn.disabled = false;
      btn.innerHTML = `${icons.send} Assign Now`;
      return;
    }

    btn.style.background = '#10B981';
    btn.innerHTML = `${icons.check} Created!`;
    
    // Refresh assignments and close modal
    setTimeout(() => {
      fetchAssignmentsFromDB().then(() => {
        renderCurrentView();
        closeCreate();
        btn.disabled = false;
        btn.style.background = '';
        btn.innerHTML = `${icons.send} Assign Now`;
      });
    }, 1000);
  } catch (error) {
    console.error('Error creating assignment:', error);
    alert('Network error: ' + error.message);
    btn.disabled = false;
    btn.innerHTML = `${icons.send} Assign Now`;
  }
}

function submitAssignment(btn) {
  btn.style.background = '#10B981';
  btn.innerHTML = `${icons.check} Submitted!`;
  btn.disabled = true;
}

function closeOnBackdrop(e, id) {
  if (e.target.id === id) {
    document.getElementById('modal-container').innerHTML = '';
    selectedAssignment = null;
  }
}

function onSearchInput(val) {
  teacherSearch = val;
  document.getElementById('view-content').innerHTML = renderTeacherView();
}

function onFilterChange(val) {
  teacherFilter = val;
  document.getElementById('view-content').innerHTML = renderTeacherView();
}

function toggleHasValue(input) {
  input.classList.toggle('has-value', input.value.trim().length > 0);
}

/**
 * Fetch assignments from the database via the controller
 */
async function fetchAssignmentsFromDB() {
  try {
    const response = await fetch('../Controller/AssignmentDataController.php?action=list', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
    });

    if (!response.ok) {
      const error = await response.json();
      console.error('Error fetching assignments:', error);
      return [];
    }

    const result = await response.json();
    
    if (result.success && result.data) {
      assignmentsData = result.data;
      return result.data;
    }

    return [];
  } catch (error) {
    console.error('Network error fetching assignments:', error);
    return [];
  }
}

/**
 * Fetch courses from the database via the controller
 */
async function fetchCoursesFromDB() {
  try {
    const response = await fetch('../Controller/CourseDataController.php?action=list', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
    });

    if (!response.ok) {
      const error = await response.json();
      console.error('Error fetching courses:', error);
      return [];
    }

    const result = await response.json();
    
    if (result.success && result.data) {
      coursesData = result.data;
      return result.data;
    }

    return [];
  } catch (error) {
    console.error('Network error fetching courses:', error);
    return [];
  }
}

/* ─── TABS ─── */
const roleTabs = [
  { id: 'teacher', label: 'Teacher View', icon: 'graduation', color: '#1565C0', bg: '#EFF6FF' },
  { id: 'parent',  label: 'Parent View',  icon: 'heart',      color: '#10B981', bg: '#F0FDF4' },
  { id: 'child',   label: 'Child View',   icon: 'baby',       color: '#E91E8C', bg: '#FFF0F7' },
];

function switchTab(role) {
  activeRole = role;
  renderCurrentView();
}

function renderCurrentView() {
  const el = document.getElementById('view-content');
  el.innerHTML = activeRole === 'teacher'
    ? renderTeacherView()
    : activeRole === 'parent'
    ? renderParentView()
    : renderChildView();
}

/* ─── INIT ─── */
document.addEventListener('DOMContentLoaded', async () => {
  // Use role from PHP session (passed via currentUserRole variable)
  if (typeof currentUserRole !== 'undefined' && currentUserRole) {
    activeRole = currentUserRole.toLowerCase();
  }

  // Hide role tabs since we're now using role-specific views
  const tabBar = document.getElementById('role-tabs');
  if (tabBar) {
    tabBar.style.display = 'none';
  }

  // Fetch courses and assignments from database for teacher view
  if (activeRole === 'teacher' || activeRole === 'admin') {
    await fetchCoursesFromDB();
    await fetchAssignmentsFromDB();
  }

  // Render the appropriate view for this user's role
  renderCurrentView();

  // Keyboard: Escape closes modals
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.getElementById('modal-container').innerHTML = '';
      selectedAssignment = null;
    }
  });
});
