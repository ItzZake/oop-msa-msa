'use strict';

/* ══════════════════════════════════════════
   USER DATA STORE
══════════════════════════════════════════ */
const ROLE_EMOJI = {
  teacher: '👩‍🏫',
  admin:   '🛡️',
  parent:  '❤️',
  child:   '👶',
};

let users = [
  { id: 1,  first: 'Emily',   last: 'Watson',   email: 'emily@wellucation.edu',  role: 'teacher', cls: 'KG1',     status: 'active'  },
  { id: 2,  first: 'James',   last: 'Rivera',   email: 'james@wellucation.edu',  role: 'teacher', cls: 'Arts',    status: 'active'  },
  { id: 3,  first: 'Aisha',   last: 'Malik',    email: 'aisha@wellucation.edu',  role: 'teacher', cls: 'KG2',     status: 'active'  },
  { id: 4,  first: 'Sarah',   last: 'Collins',  email: 'sarah@wellucation.edu',  role: 'admin',   cls: 'All',     status: 'active'  },
  { id: 5,  first: 'Mrs.',    last: 'Johnson',  email: 'johnson@email.com',      role: 'parent',  cls: 'KG1',     status: 'active'  },
  { id: 6,  first: 'Mr.',     last: 'Williams', email: 'williams@email.com',     role: 'parent',  cls: 'Nursery', status: 'pending' },
  { id: 7,  first: 'Emma',    last: 'Johnson',  email: '',                        role: 'child',   cls: 'KG1',     status: 'active'  },
  { id: 8,  first: 'Noah',    last: 'Williams', email: '',                        role: 'child',   cls: 'Nursery', status: 'active'  },
];

let nextId   = 9;
let deleteTarget = null; // { type: 'user'|'account', id? }
let activeFilter = 'all';
let searchQuery  = '';

/* ══════════════════════════════════════════
   TABS
══════════════════════════════════════════ */
function initTabs() {
  const tabs   = document.querySelectorAll('.ep-tab');
  const panels = document.querySelectorAll('.ep-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      const panel = document.getElementById('panel-' + tab.dataset.tab);
      if (panel) panel.classList.add('active');
    });
  });
}

/* ══════════════════════════════════════════
   USER LIST RENDER
══════════════════════════════════════════ */
function renderUsers() {
  const list = document.getElementById('userList');
  if (!list) return;

  const filtered = users.filter(u => {
    const matchRole   = activeFilter === 'all' || u.role === activeFilter;
    const q = searchQuery.toLowerCase();
    const matchSearch = !q ||
      (u.first + ' ' + u.last).toLowerCase().includes(q) ||
      u.email.toLowerCase().includes(q) ||
      u.cls.toLowerCase().includes(q) ||
      u.role.toLowerCase().includes(q);
    return matchRole && matchSearch;
  });

  if (filtered.length === 0) {
    list.innerHTML = `
      <div class="ep-user-empty">
        <span>🔍</span>
        No users found matching your filters.
      </div>`;
    return;
  }

  list.innerHTML = filtered.map(u => `
    <div class="ep-user-row" data-id="${u.id}">
      <span class="ep-user-emoji">${ROLE_EMOJI[u.role] || '👤'}</span>
      <div class="ep-user-info">
        <div class="ep-user-name">${u.first} ${u.last}</div>
        <div class="ep-user-sub">
          <span class="ep-role-${u.role}" style="font-weight:700;text-transform:capitalize">${u.role}</span>
          ${u.cls ? ' · ' + u.cls : ''}
          ${u.email ? ' · ' + u.email : ''}
        </div>
      </div>
      <span class="ep-user-badge ep-user-badge--${u.status}">${u.status}</span>
      <div class="ep-user-actions">
        <button class="ep-action-btn ep-action-btn--edit" onclick="editUser(${u.id})">✏️ Edit</button>
        <button class="ep-action-btn ep-action-btn--remove" onclick="promptRemoveUser(${u.id})">🗑️ Remove</button>
      </div>
    </div>
  `).join('');
}

/* ══════════════════════════════════════════
   FILTER PILLS
══════════════════════════════════════════ */
function initFilterPills() {
  const pills = document.querySelectorAll('.ep-pill');
  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      activeFilter = pill.dataset.filter;
      renderUsers();
    });
  });
}

/* ══════════════════════════════════════════
   SEARCH
══════════════════════════════════════════ */
function filterUsers(val) {
  searchQuery = val;
  renderUsers();
}
window.filterUsers = filterUsers;

/* ══════════════════════════════════════════
   ADD USER MODAL
══════════════════════════════════════════ */
function openAddModal() {
  clearAddForm();
  document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
  document.getElementById('addModal').classList.add('hidden');
}

function clearAddForm() {
  ['newFirst','newLast','newEmail','newClass'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  const role   = document.getElementById('newRole');
  const status = document.getElementById('newStatus');
  if (role)   role.value   = 'teacher';
  if (status) status.value = 'active';
}

function addUser() {
  const first  = document.getElementById('newFirst').value.trim();
  const last   = document.getElementById('newLast').value.trim();
  const email  = document.getElementById('newEmail').value.trim();
  const role   = document.getElementById('newRole').value;
  const cls    = document.getElementById('newClass').value.trim();
  const status = document.getElementById('newStatus').value;

  if (!first || !last) {
    showToast('⚠️ Please enter first and last name.', true);
    return;
  }
  if (role !== 'child' && email && !email.includes('@')) {
    showToast('⚠️ Please enter a valid email.', true);
    return;
  }

  users.push({ id: nextId++, first, last, email, role, cls, status });
  closeAddModal();
  renderUsers();
  showToast(`✅ ${first} ${last} added successfully!`);
}

/* ══════════════════════════════════════════
   EDIT USER (inline via modal reuse)
══════════════════════════════════════════ */
function editUser(id) {
  const u = users.find(x => x.id === id);
  if (!u) return;

  document.getElementById('newFirst').value  = u.first;
  document.getElementById('newLast').value   = u.last;
  document.getElementById('newEmail').value  = u.email;
  document.getElementById('newRole').value   = u.role;
  document.getElementById('newClass').value  = u.cls;
  document.getElementById('newStatus').value = u.status;

  // Swap button to update mode
  const addBtn = document.getElementById('confirmAdd');
  addBtn.textContent = '💾 Update User';
  addBtn.onclick = () => updateUser(id);

  document.querySelector('.ep-modal__header h3').textContent = '✏️ Edit User';
  document.getElementById('addModal').classList.remove('hidden');
}

function updateUser(id) {
  const u = users.find(x => x.id === id);
  if (!u) return;

  u.first  = document.getElementById('newFirst').value.trim()  || u.first;
  u.last   = document.getElementById('newLast').value.trim()   || u.last;
  u.email  = document.getElementById('newEmail').value.trim();
  u.role   = document.getElementById('newRole').value;
  u.cls    = document.getElementById('newClass').value.trim();
  u.status = document.getElementById('newStatus').value;

  closeAddModal();
  resetAddModal();
  renderUsers();
  showToast(`✅ ${u.first} ${u.last} updated!`);
}

function resetAddModal() {
  const addBtn = document.getElementById('confirmAdd');
  addBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add User`;
  addBtn.onclick = addUser;
  document.querySelector('.ep-modal__header h3').textContent = '➕ Add New User';
}
window.editUser = editUser;

/* ══════════════════════════════════════════
   REMOVE USER
══════════════════════════════════════════ */
function promptRemoveUser(id) {
  const u = users.find(x => x.id === id);
  if (!u) return;
  deleteTarget = { type: 'user', id };
  document.getElementById('deleteModalMsg').textContent =
    `Remove "${u.first} ${u.last}" from the system?`;
  document.getElementById('deleteModal').classList.remove('hidden');
}
window.promptRemoveUser = promptRemoveUser;

function executeDelete() {
  if (!deleteTarget) return;

  if (deleteTarget.type === 'user') {
    const u = users.find(x => x.id === deleteTarget.id);
    const name = u ? `${u.first} ${u.last}` : 'User';
    users = users.filter(x => x.id !== deleteTarget.id);
    renderUsers();
    showToast(`🗑️ ${name} removed.`);
  } else if (deleteTarget.type === 'account') {
    showToast('🗑️ Account deletion request sent.');
  }

  document.getElementById('deleteModal').classList.add('hidden');
  deleteTarget = null;
}

/* ══════════════════════════════════════════
   PERSONAL INFO
══════════════════════════════════════════ */
window.savePersonal = function () {
  const name = document.getElementById('displayName').value.trim();
  if (name) {
    document.getElementById('sidebarName').textContent = name;
  }
  showToast('✅ Profile updated successfully!');
};

window.resetPersonal = function () {
  document.getElementById('firstName').value   = 'Emily';
  document.getElementById('lastName').value    = 'Watson';
  document.getElementById('displayName').value = 'Ms. Emily Watson';
  document.getElementById('email').value       = 'emily@wellucation.edu';
  document.getElementById('phone').value       = '+1 (555) 234-5678';
  document.getElementById('classField').value  = 'KG1 – Sunflower';
  document.getElementById('bio').value         = 'Passionate early childhood educator with 12 years of experience nurturing young learners.';
  showToast('↩️ Changes discarded.');
};

/* ══════════════════════════════════════════
   PASSWORD
══════════════════════════════════════════ */
window.changePassword = function () {
  const curr    = document.getElementById('currentPwd').value;
  const newPwd  = document.getElementById('newPwd').value;
  const confirm = document.getElementById('confirmPwd').value;

  if (!curr)         { showToast('⚠️ Enter your current password.', true); return; }
  if (newPwd.length < 8) { showToast('⚠️ New password must be at least 8 characters.', true); return; }
  if (newPwd !== confirm) { showToast('⚠️ Passwords do not match.', true); return; }

  showToast('🔒 Password updated successfully!');
  document.getElementById('currentPwd').value = '';
  document.getElementById('newPwd').value     = '';
  document.getElementById('confirmPwd').value = '';
  updatePwdStrength('');
};

/* ── Password strength ─────────────────── */
function updatePwdStrength(pwd) {
  const fill  = document.getElementById('pwdFill');
  const label = document.getElementById('pwdLabel');
  if (!fill || !label) return;

  let score = 0;
  if (pwd.length >= 8)             score++;
  if (/[A-Z]/.test(pwd))           score++;
  if (/[0-9]/.test(pwd))           score++;
  if (/[^A-Za-z0-9]/.test(pwd))   score++;

  const levels = [
    { pct: '0%',   color: '#e5e7eb', text: 'Enter a password' },
    { pct: '25%',  color: '#EF4444', text: 'Weak' },
    { pct: '50%',  color: '#F59E0B', text: 'Fair' },
    { pct: '75%',  color: '#3B82F6', text: 'Good' },
    { pct: '100%', color: '#10B981', text: 'Strong ✅' },
  ];
  const level = levels[pwd.length === 0 ? 0 : score] || levels[1];
  fill.style.width      = level.pct;
  fill.style.background = level.color;
  label.textContent     = level.text;
  label.style.color     = level.color;
}

/* ── Password toggle visibility ────────── */
function initPwdToggles() {
  document.querySelectorAll('.ep-pwd-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = document.getElementById(btn.dataset.target);
      if (!target) return;
      const isText = target.type === 'text';
      target.type  = isText ? 'password' : 'text';
      btn.textContent = isText ? '👁️' : '🙈';
    });
  });

  const newPwdInput = document.getElementById('newPwd');
  if (newPwdInput) {
    newPwdInput.addEventListener('input', () => updatePwdStrength(newPwdInput.value));
  }
}

/* ══════════════════════════════════════════
   AVATAR UPLOAD
══════════════════════════════════════════ */
function initAvatarUpload() {
  const input = document.getElementById('avatarUpload');
  const img   = document.getElementById('avatarImg');
  if (!input || !img) return;

  input.addEventListener('change', () => {
    const file = input.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) {
      showToast('⚠️ Please select an image file.', true);
      return;
    }
    const reader = new FileReader();
    reader.onload = e => {
      img.src          = e.target.result;
      img.style.display = 'block';
      showToast('📸 Photo updated!');
    };
    reader.readAsDataURL(file);
  });

  img.addEventListener('error', () => {
    img.style.display = 'none';
    const fallback = img.nextElementSibling;
    if (fallback) fallback.style.display = 'block';
  });
}

/* ══════════════════════════════════════════
   TOAST
══════════════════════════════════════════ */
let toastTimer = null;
function showToast(msg, isError = false) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = msg;
  toast.style.background = isError ? '#EF4444' : '#1f2937';
  toast.classList.remove('hidden');
  if (toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.add('hidden'), 3000);
}
window.showToast = showToast;

/* ══════════════════════════════════════════
   NAVBAR (hamburger + dropdown)
══════════════════════════════════════════ */
function initNavbar() {
  const hamburger    = document.getElementById('hamburger');
  const mobileMenu   = document.getElementById('mobileMenu');
  const iconMenu     = hamburger?.querySelector('.icon-menu');
  const iconClose    = hamburger?.querySelector('.icon-close');
  const moreDropdown = document.getElementById('moreDropdown');
  const moreBtn      = document.getElementById('moreBtn');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', isOpen);
      mobileMenu.setAttribute('aria-hidden', !isOpen);
      iconMenu?.classList.toggle('hidden', isOpen);
      iconClose?.classList.toggle('hidden', !isOpen);
    });
  }

  if (moreBtn && moreDropdown) {
    moreBtn.addEventListener('click', e => {
      e.stopPropagation();
      const isOpen = moreDropdown.classList.toggle('open');
      moreBtn.setAttribute('aria-expanded', isOpen);
    });
    document.addEventListener('click', e => {
      if (!moreDropdown.contains(e.target)) {
        moreDropdown.classList.remove('open');
        moreBtn.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        moreDropdown.classList.remove('open');
        moreBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }
}

/* ══════════════════════════════════════════
   INIT
══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  initTabs();
  initFilterPills();
  initPwdToggles();
  initAvatarUpload();
  initNavbar();
  renderUsers();

  /* ── Add user modal ── */
  document.getElementById('openAddModal')?.addEventListener('click', openAddModal);
  document.getElementById('closeModal')?.addEventListener('click', () => {
    closeAddModal();
    resetAddModal();
  });
  document.getElementById('cancelModal')?.addEventListener('click', () => {
    closeAddModal();
    resetAddModal();
  });
  document.getElementById('confirmAdd')?.addEventListener('click', addUser);

  /* Close modal on overlay click */
  document.getElementById('addModal')?.addEventListener('click', e => {
    if (e.target === document.getElementById('addModal')) {
      closeAddModal();
      resetAddModal();
    }
  });

  /* ── Delete modal ── */
  document.getElementById('closeDeleteModal')?.addEventListener('click', () => {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteTarget = null;
  });
  document.getElementById('cancelDelete')?.addEventListener('click', () => {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteTarget = null;
  });
  document.getElementById('confirmDelete')?.addEventListener('click', executeDelete);

  document.getElementById('deleteModal')?.addEventListener('click', e => {
    if (e.target === document.getElementById('deleteModal')) {
      document.getElementById('deleteModal').classList.add('hidden');
      deleteTarget = null;
    }
  });

  /* ── Delete account ── */
  document.getElementById('deleteAccountBtn')?.addEventListener('click', () => {
    deleteTarget = { type: 'account' };
    document.getElementById('deleteModalMsg').textContent =
      'Permanently delete your account? All data will be lost forever.';
    document.getElementById('deleteModal').classList.remove('hidden');
  });

  /* ── Escape key closes modals ── */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.getElementById('addModal')?.classList.add('hidden');
      document.getElementById('deleteModal')?.classList.add('hidden');
      resetAddModal();
      deleteTarget = null;
    }
  });
});
