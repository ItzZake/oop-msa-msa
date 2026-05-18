/* ── PAGE NAVIGATION ── */
function navigate(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  const el = document.getElementById('page-' + page);
  if (el) {
    el.classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  document.querySelectorAll('#desktopNav a, #mobileNav a').forEach(a => a.classList.remove('active'));
  const navEl = document.getElementById('nav-' + page);
  if (navEl) navEl.classList.add('active');

  if (page === 'attendance') initAttendance();
  if (page === 'profiles') showProfileRole('teacher');
  if (page === 'dashboard') initDashGrid();
}

/* ── MOBILE MENU ── */
function toggleMobileMenu() {
  document.getElementById('mobileNav').classList.toggle('open');
}
function closeMobileMenu() {
  document.getElementById('mobileNav').classList.remove('open');
}

/* ── TOAST ── */
function showToast(msg, isError) {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = 'toast' + (isError ? ' error' : '');
  toast.innerHTML = '<span style="font-size:1.25rem;">' + (isError ? '❌' : '✅') + '</span><span style="font-size:0.875rem;font-weight:600;color:var(--gray-800);">' + msg + '</span>';
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

/* ── MODALS ── */
function openCreateAssignment() {
  document.getElementById('createAssignmentModal').style.display = 'flex';
}
function openAssignmentDetail(title) {
  document.getElementById('assignDetailTitle').textContent = title;
  document.getElementById('assignmentDetailModal').style.display = 'flex';
}
function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}

/* ── LOGIN ── */
function switchLoginTab(tab) {
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const loginBtn = document.getElementById('loginTabBtn');
  const regBtn = document.getElementById('registerTabBtn');
  if (tab === 'login') {
    loginForm.style.display = ''; registerForm.style.display = 'none';
    loginBtn.style.background = 'white'; loginBtn.style.color = 'var(--blue)'; loginBtn.style.boxShadow = '0 1px 4px rgba(0,0,0,0.1)';
    regBtn.style.background = 'transparent'; regBtn.style.color = 'var(--gray-500)'; regBtn.style.boxShadow = '';
  } else {
    loginForm.style.display = 'none'; registerForm.style.display = '';
    regBtn.style.background = 'white'; regBtn.style.color = 'var(--blue)'; regBtn.style.boxShadow = '0 1px 4px rgba(0,0,0,0.1)';
    loginBtn.style.background = 'transparent'; loginBtn.style.color = 'var(--gray-500)'; loginBtn.style.boxShadow = '';
  }
}
function handleLoginSubmit(e) {
  e.preventDefault();
  showToast('✅ Login successful! Welcome back.');
  setTimeout(() => navigate('dashboard'), 800);
}
function handleRegisterSubmit(e) {
  e.preventDefault();
  showToast('✅ Account created! Please login.');
  setTimeout(() => switchLoginTab('login'), 800);
}

/* ── CONTACT / ENROLL / PAYMENT ── */
function handleContactSubmit(e) {
  e.preventDefault();
  document.getElementById('contactSuccess').style.display = 'block';
  e.target.reset();
  showToast('✅ Message sent! We\'ll reply within 24 hours.');
}
function handleEnrollSubmit(e) {
  e.preventDefault();
  showToast('🎉 Enrollment submitted! We\'ll contact you within 48 hours.');
  e.target.reset();
}
function handlePaymentSubmit(e) {
  e.preventDefault();
  showToast('✅ Payment of $950.00 processed successfully!');
}
function formatCard(input) {
  let v = input.value.replace(/\D/g,'').substring(0,16);
  input.value = v.replace(/(.{4})/g,'$1 ').trim();
}
function selectPayMethod(method) {
  ['card','debit','paypal'].forEach(m => {
    document.getElementById('pm-' + m).classList.toggle('selected', m === method);
  });
  document.getElementById('cardForm').style.display = (method === 'paypal') ? 'none' : '';
  document.getElementById('paypalForm').style.display = (method === 'paypal') ? '' : 'none';
}

/* ── SUBSCRIPTION BILLING TOGGLE ── */
let billingYearly = false;
const prices = { basic: [850, 680], premium: [1200, 960], elite: [1650, 1320] };
function toggleBilling() {
  billingYearly = !billingYearly;
  const btn = document.getElementById('billingToggle');
  btn.classList.toggle('on', billingYearly);
  btn.classList.toggle('off', !billingYearly);
  document.getElementById('monthlyLabel').style.color = billingYearly ? 'var(--gray-400)' : 'var(--pink)';
  document.getElementById('yearlyLabel').style.color = billingYearly ? 'var(--pink)' : 'var(--gray-400)';
  document.getElementById('saveBadge').style.display = billingYearly ? '' : 'none';
  const idx = billingYearly ? 1 : 0;
  document.getElementById('basic-price').textContent = '$' + prices.basic[idx].toLocaleString();
  document.getElementById('premium-price').textContent = '$' + prices.premium[idx].toLocaleString();
  document.getElementById('elite-price').textContent = '$' + prices.elite[idx].toLocaleString();
}

/* ── DASHBOARD NAV ── */
function setDashNav(btn, section) {
  document.querySelectorAll('.sidebar-nav-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const topbar = document.querySelector('.dashboard-topbar h2');
  if (topbar) topbar.textContent = section.charAt(0).toUpperCase() + section.slice(1);
}
function initDashGrid() {
  document.querySelectorAll('.dash-grid, .dash-charts-grid').forEach(g => {
    if (window.innerWidth < 768) {
      g.style.gridTemplateColumns = '1fr';
    }
  });
}

/* ── ATTENDANCE ── */
const students = [
  { name: 'Emma Johnson', emoji: '👧', class: 'KG1', status: null },
  { name: 'Noah Williams', emoji: '👦', class: 'KG1', status: null },
  { name: 'Sophia Brown',  emoji: '👧', class: 'KG1', status: null },
  { name: 'Liam Davis',    emoji: '👦', class: 'KG1', status: null },
  { name: 'Olivia Miller', emoji: '👧', class: 'KG1', status: null },
  { name: 'Mason Wilson',  emoji: '👦', class: 'KG1', status: null },
  { name: 'Ava Chen',      emoji: '👧', class: 'KG1', status: null },
  { name: 'James Park',    emoji: '👦', class: 'KG1', status: null },
];
function initAttendance() {
  students.forEach(s => s.status = null);
  renderStudentRows();
  buildCalendar();
}
function renderStudentRows() {
  const container = document.getElementById('studentRows');
  if (!container) return;
  container.innerHTML = students.map((s,i) => `
    <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1rem;border-top:1px solid #F9FAFB;flex-wrap:wrap;gap:0.5rem;" id="row-${i}">
      <div style="display:flex;align-items:center;gap:0.75rem;"><span style="font-size:1.5rem;">${s.emoji}</span><div><div style="font-weight:700;font-size:0.875rem;">${s.name}</div><div style="font-size:0.75rem;color:var(--gray-400);">${s.class}</div></div></div>
      <div style="display:flex;gap:0.5rem;">
        <button onclick="setStatus(${i},'present')" id="btn-p-${i}" style="padding:0.375rem 0.75rem;border-radius:0.625rem;border:none;cursor:pointer;font-size:0.8125rem;font-weight:700;background:${s.status==='present'?'var(--green)':'#F0FDF4'};color:${s.status==='present'?'white':'var(--green)'}">✅</button>
        <button onclick="setStatus(${i},'absent')"  id="btn-a-${i}" style="padding:0.375rem 0.75rem;border-radius:0.625rem;border:none;cursor:pointer;font-size:0.8125rem;font-weight:700;background:${s.status==='absent'?'#EF4444':'#FEF2F2'};color:${s.status==='absent'?'white':'#EF4444'}">❌</button>
        <button onclick="setStatus(${i},'late')"   id="btn-l-${i}" style="padding:0.375rem 0.75rem;border-radius:0.625rem;border:none;cursor:pointer;font-size:0.8125rem;font-weight:700;background:${s.status==='late'?'var(--yellow)':'#FFFBEB'};color:${s.status==='late'?'white':'var(--yellow)'}">⏰</button>
      </div>
    </div>`).join('');
  updateAttStats();
}
function setStatus(idx, status) {
  students[idx].status = status;
  renderStudentRows();
}
function updateAttStats() {
  const present = students.filter(s=>s.status==='present').length;
  const absent  = students.filter(s=>s.status==='absent').length;
  const late    = students.filter(s=>s.status==='late').length;
  const marked  = present + absent + late;
  ['statPresent','statAbsent','statLate'].forEach((id,i) => {
    const el = document.getElementById(id);
    if (el) el.textContent = [present,absent,late][i];
  });
  ['markedCount','markedCount2'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.textContent = marked + '/' + students.length + ' marked';
  });
  const submitBtn = document.getElementById('submitAttBtn');
  if (submitBtn) {
    const allMarked = marked === students.length;
    submitBtn.disabled = !allMarked;
    submitBtn.style.opacity = allMarked ? '1' : '0.5';
    submitBtn.style.cursor = allMarked ? 'pointer' : 'not-allowed';
  }
}
function markAllPresent() {
  students.forEach(s => s.status = 'present');
  renderStudentRows();
  showToast('✅ All students marked present!');
}
function submitAttendance() {
  showToast('✅ Attendance submitted successfully!');
}
function setAttView(view) {
  ['teacher','parent','admin'].forEach(v => {
    document.getElementById('att-view-'+v).style.display = v===view ? '' : 'none';
    const tab = document.getElementById('att-tab-'+v);
    if (tab) tab.style.background = v===view ? 'var(--blue)' : '#F0FDF4';
    if (tab) tab.style.color = v===view ? 'white' : 'var(--green)';
  });
  if (view==='teacher') { document.getElementById('att-tab-teacher').style.background='var(--blue)';document.getElementById('att-tab-teacher').style.color='white'; }
}
function buildCalendar() {
  const grid = document.getElementById('calendarGrid');
  if (!grid) return;
  const statuses = ['','','','','','','p','p','p','a','p','p','','','p','p','l','p','p','','','p','a','p','p','p','','','p','p','p'];
  let html = '';
  for (let d = 1; d <= 31; d++) {
    const s = statuses[d] || '';
    const bg = s==='p'?'var(--green)':s==='a'?'#EF4444':s==='l'?'var(--yellow)':'var(--gray-100)';
    const color = s?'white':'var(--gray-400)';
    html += `<div class="cal-day" style="background:${bg};color:${color};">${d}</div>`;
  }
  grid.innerHTML = html;
}

/* ── REPORTS TABS ── */
function setReportTab(btn, tab) {
  ['overview','attendance','academic','students','saved'].forEach(t => {
    const el = document.getElementById('report-' + t);
    if (el) el.style.display = t===tab ? '' : 'none';
  });
  document.querySelectorAll('.view-tab-btn').forEach(b => {
    b.style.background = '#FFF0F7'; b.style.color = 'var(--pink)';
  });
  btn.style.background = 'var(--pink)'; btn.style.color = 'white';
}

/* ── ASSIGNMENTS VIEWS ── */
function setAssignView(view) {
  ['teacher','parent','child'].forEach(v => {
    document.getElementById('assign-view-'+v).style.display = v===view ? '' : 'none';
    const tab = document.getElementById('assign-tab-'+v);
    if (!tab) return;
    if (v==='teacher') { tab.style.background = v===view?'var(--blue)':'#EFF6FF'; tab.style.color = v===view?'white':'var(--blue)'; }
    else if (v==='parent') { tab.style.background = v===view?'var(--green)':'#F0FDF4'; tab.style.color = v===view?'white':'var(--green)'; }
    else { tab.style.background = v===view?'var(--pink)':'#FFF0F7'; tab.style.color = v===view?'white':'var(--pink)'; }
  });
}

/* ── PROFILES ── */
function setProfileRole(role) { showProfileRole(role); }
function showProfileRole(role) {
  ['teacher','admin','parent','child'].forEach(r => {
    const el = document.getElementById('profile-view-'+r);
    if (el) el.style.display = r===role ? '' : 'none';
    const tab = document.getElementById('profile-tab-'+r);
    if (!tab) return;
    const colors = {teacher:['var(--blue)','#EFF6FF'],admin:['var(--pink)','#FFF0F7'],parent:['var(--green)','#F0FDF4'],child:['var(--yellow)','#FFFBEB']};
    tab.style.background = r===role ? colors[r][0] : colors[r][1];
    tab.style.color = r===role ? 'white' : colors[r][0];
  });
}

/* ── EXCUSE ── */
function setExcuseTab(tab) {
  ['submit','history'].forEach(t => {
    document.getElementById('excuse-view-'+t).style.display = t===tab ? '' : 'none';
    const btn = document.getElementById('excuse-tab-'+t);
    btn.style.background = t===tab ? 'var(--pink)' : '#FFF0F7';
    btn.style.color = t===tab ? 'white' : 'var(--pink)';
  });
}
function handleExcuseSubmit(e) {
  e.preventDefault();
  showToast('✅ Excuse submitted! Awaiting approval.');
  e.target.reset();
}
function filterExcuses(q) {
  const items = document.querySelectorAll('#excuseList .excuse-card');
  q = q.toLowerCase();
  items.forEach(item => {
    const name = (item.dataset.name || '').toLowerCase();
    const reason = (item.dataset.reason || '').toLowerCase();
    item.style.display = (!q || name.includes(q) || reason.includes(q)) ? '' : 'none';
  });
}

/* ── MESSAGES ── */
function selectChat(el, name, avatar, role, online) {
  document.querySelectorAll('.chat-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('chatHeaderName').textContent = name;
  document.getElementById('chatHeaderRole').textContent = role;
  document.getElementById('chatHeaderAvatar').innerHTML = avatar + (online ? '<span style="position:absolute;bottom:0;right:0;width:0.625rem;height:0.625rem;background:var(--green);border-radius:50%;border:2px solid white;"></span>' : '');
}
function filterChats(q) {
  document.querySelectorAll('.chat-item').forEach(item => {
    const name = item.querySelector('[style*="font-weight:700"]')?.textContent || '';
    item.style.display = (!q || name.toLowerCase().includes(q.toLowerCase())) ? '' : 'none';
  });
}
function sendMessage(e) {
  e.preventDefault();
  const input = document.getElementById('msgInput');
  const text = input.value.trim();
  if (!text) return;
  const messages = document.getElementById('chatMessages');
  const msg = document.createElement('div');
  msg.className = 'message me';
  const now = new Date();
  msg.innerHTML = `<div class="message-bubble">${text}</div><div class="message-time">${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')}</div>`;
  messages.appendChild(msg);
  messages.scrollTop = messages.scrollHeight;
  input.value = '';
}

/* ── SETTINGS ── */
function setSettingsTab(btn, tab) {
  ['profile','notifications','security','preferences'].forEach(t => {
    const el = document.getElementById('settings-view-'+t);
    if (el) el.style.display = t===tab ? '' : 'none';
  });
  document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}
function toggleSwitch(btn) {
  btn.classList.toggle('on');
  btn.classList.toggle('off');
}
function handleSettingsSave(e, msg) {
  e.preventDefault();
  showToast(msg || '✅ Settings saved!');
}

/* ── ASSIGNMENT CREATION ── */
function handleCreateAssignment(e) {
  e.preventDefault();
  closeModal('createAssignmentModal');
  showToast('✅ Assignment created successfully!');
}

/* ── INIT ── */
window.addEventListener('DOMContentLoaded', () => {
  navigate('home');
  setTimeout(initAttendance, 100);
});