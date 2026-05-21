/* ── DATA ── */
const IMAGES = {
  staffImg1:
    "https://images.unsplash.com/photo-1746513399803-e988cc54e812?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=400",
  staffImg2:
    "https://images.unsplash.com/photo-1573496800808-56566a492b63?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=400",
  familyImg:
    "https://images.unsplash.com/photo-1563178929-0fa2e4c1c021?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=400",
};

const FALLBACK_EMOJI = { staffImg1: "👩‍🏫", staffImg2: "👩‍💼", familyImg: "👩" };
const API_ENDPOINT = new URL(
  "../user_api.php",
  document.currentScript?.src || window.location.href,
).href;
let adminUsers = [];
let adminUsersLoaded = false;
let adminModalMode = "add";
let adminModalUserId = null;

function mapApiUser(item) {
  return {
    id: Number(item.userId ?? item.userID ?? item.id ?? 0),
    first: item.firstName ?? item.firstname ?? item.first ?? "",
    last: item.lastName ?? item.Lastname ?? item.last ?? "",
    email: item.email ?? "",
    role: (item.role ?? item.Role ?? "teacher").toLowerCase(),
    cls: item.cls ?? "",
    status: (
      item.status ?? ((item.isActive ?? item.IsActive) ? "active" : "pending")
    ).toLowerCase(),
  };
}

async function apiRequest(method, body = null, queryParams = "") {
  const url = `${API_ENDPOINT}${queryParams}`;
  const options = {
    method,
    headers: { "Content-Type": "application/json" },
  };
  if (body !== null) {
    options.body = JSON.stringify(body);
  }

  const response = await fetch(url, options);
  const text = await response.text();
  let data;
  try {
    data = JSON.parse(text);
  } catch {
    throw new Error("Invalid server response");
  }
  if (!response.ok || !data.success) {
    throw new Error(data.error || `Request failed (${response.status})`);
  }
  return data;
}

async function loadAdminUsers() {
  try {
    const response = await apiRequest("GET");
    adminUsers = (response.users || []).map(mapApiUser);
    adminUsersLoaded = true;
  } catch (error) {
    adminUsersLoaded = true;
    adminUsers = [];
    console.warn("Admin users load failed:", error.message);
  }
}

function getRoleEmoji(role) {
  return (
    {
      teacher: "👩‍🏫",
      admin: "🛡️",
      parent: "❤️",
      child: "👶",
    }[role] || "👤"
  );
}

function showAdminError(message) {
  const modalError = document.getElementById("adminModalError");
  if (modalError) {
    modalError.textContent = message;
    modalError.style.display = "block";
    return;
  }
  alert(message);
}

function clearAdminError() {
  const modalError = document.getElementById("adminModalError");
  if (modalError) {
    modalError.textContent = "";
    modalError.style.display = "none";
  }
}

function initAdminModal() {
  if (document.getElementById("adminUserModal")) return;

  document.body.insertAdjacentHTML(
    "beforeend",
    `<div class="admin-modal-overlay hidden" id="adminUserModal">
      <div class="admin-modal">
        <div class="admin-modal-header">
          <h3 id="adminModalTitle">Add User</h3>
          <button type="button" class="admin-modal-close" id="adminModalClose">×</button>
        </div>
        <div class="admin-modal-body">
          <div class="admin-modal-row"><label>First Name</label><input id="adminModalFirst" type="text" /></div>
          <div class="admin-modal-row"><label>Last Name</label><input id="adminModalLast" type="text" /></div>
          <div class="admin-modal-row"><label>Email</label><input id="adminModalEmail" type="email" autocomplete="email" /></div>
          <div class="admin-modal-row"><label>Password</label><input id="adminModalPassword" type="password" autocomplete="new-password" placeholder="Enter a password" /></div>
          <div class="admin-modal-row"><label>Role</label><select id="adminModalRole"><option value="teacher">Teacher</option><option value="admin">Admin</option><option value="parent">Parent</option><option value="child">Child</option></select></div>
          <div class="admin-modal-row"><label>Class</label><input id="adminModalClass" type="text" /></div>
          <div class="admin-modal-row"><label>Status</label><select id="adminModalStatus"><option value="active">Active</option><option value="pending">Pending</option></select></div>
          <div id="adminModalError" class="admin-modal-error" style="display:none"></div>
        </div>
        <div class="admin-modal-footer">
          <button type="button" class="btn-remove" id="adminModalCancel">Cancel</button>
          <button type="button" class="btn-add" id="adminModalSave">Save</button>
        </div>
      </div>
    </div>`,
  );

  document
    .getElementById("adminModalClose")
    .addEventListener("click", closeAdminUserModal);
  document
    .getElementById("adminModalCancel")
    .addEventListener("click", closeAdminUserModal);
  document
    .getElementById("adminModalSave")
    .addEventListener("click", saveAdminUserModal);
}

function showAdminUserModal(mode, userId = null) {
  const modal = document.getElementById("adminUserModal");
  if (!modal) return;

  adminModalMode = mode;
  adminModalUserId = userId;
  const title = document.getElementById("adminModalTitle");
  const first = document.getElementById("adminModalFirst");
  const last = document.getElementById("adminModalLast");
  const email = document.getElementById("adminModalEmail");
  const role = document.getElementById("adminModalRole");
  const cls = document.getElementById("adminModalClass");
  const status = document.getElementById("adminModalStatus");

  const password = document.getElementById("adminModalPassword");

  if (mode === "edit" && userId !== null) {
    const user = adminUsers.find((u) => u.id === userId);
    if (!user) return;
    title.textContent = "Edit User";
    first.value = user.first;
    last.value = user.last;
    email.value = user.email;
    password.value = "";
    role.value = user.role;
    cls.value = user.cls;
    status.value = user.status;
  } else {
    title.textContent = "Add User";
    first.value = "";
    last.value = "";
    email.value = "";
    password.value = "";
    role.value = "teacher";
    cls.value = "";
    status.value = "active";
  }

  clearAdminError();
  modal.classList.remove("hidden");
}

function closeAdminUserModal() {
  const modal = document.getElementById("adminUserModal");
  if (!modal) return;
  modal.classList.add("hidden");
}

async function saveAdminUserModal() {
  const first = document.getElementById("adminModalFirst").value.trim();
  const last = document.getElementById("adminModalLast").value.trim();
  const email = document.getElementById("adminModalEmail").value.trim();
  const password = document.getElementById("adminModalPassword").value;
  const role = document.getElementById("adminModalRole").value;
  const cls = document.getElementById("adminModalClass").value.trim();
  const status = document.getElementById("adminModalStatus").value;

  if (!first || !last) {
    showAdminError("First and last name are required.");
    return;
  }
  if (role !== "child" && email && !email.includes("@")) {
    showAdminError("Please enter a valid email address.");
    return;
  }

  if (adminModalMode === "add") {
    if (!password) {
      showAdminError("Password is required for new users.");
      return;
    }
    if (password.length < 8) {
      showAdminError("Password must be at least 8 characters.");
      return;
    }
  }

  if (password && password.length > 0 && password.length < 8) {
    showAdminError("Password must be at least 8 characters.");
    return;
  }

  try {
    if (adminModalMode === "edit" && adminModalUserId !== null) {
      const updateBody = {
        id: adminModalUserId,
        first,
        last,
        email,
        role,
        cls,
        status,
      };
      if (password) {
        updateBody.password = password;
      }
      const response = await apiRequest("PUT", updateBody);
      const updated = mapApiUser(response.user);
      const index = adminUsers.findIndex((u) => u.id === adminModalUserId);
      if (index > -1) {
        adminUsers[index] = updated;
      }
    } else {
      const response = await apiRequest("POST", {
        first,
        last,
        email,
        password,
        role,
        cls,
        status,
      });
      adminUsers.push(mapApiUser(response.user));
    }
    closeAdminUserModal();
    renderAdminTabIfActive();
  } catch (error) {
    showAdminError(error.message);
  }
}

function openAdminAddUser() {
  showAdminUserModal("add");
}

function editAdminUser(id) {
  showAdminUserModal("edit", id);
}

async function promptAdminRemoveUser(id) {
  if (!confirm("Delete this user permanently?")) return;
  try {
    await apiRequest("DELETE", null, `?id=${id}`);
    adminUsers = adminUsers.filter((u) => u.id !== id);
    renderAdminTabIfActive();
  } catch (error) {
    showAdminError(error.message);
  }
}

function renderAdminTabIfActive() {
  const contentEl = document.getElementById("adminTabs-content");
  if (!contentEl) return;
  const activeButton = document.querySelector("#adminTabs .tab-btn.active");
  const activeTab = activeButton?.dataset.tab ?? "overview";
  contentEl.innerHTML = renderAdminContent(activeTab);
  animateProgressBars();
}

window.openAdminAddUser = openAdminAddUser;
window.editAdminUser = editAdminUser;
window.promptAdminRemoveUser = promptAdminRemoveUser;

/* ── HELPERS ── */
function stars(count = 5) {
  return Array.from(
    { length: count },
    () => '<span class="star">★</span>',
  ).join("");
}

function statusBadge(status) {
  const map = {
    present: { bg: "#F0FDF4", color: "#10B981", label: "✅ Present" },
    absent: { bg: "#FEF2F2", color: "#EF4444", label: "❌ Absent" },
    late: { bg: "#FFFBEB", color: "#F59E0B", label: "⏰ Late" },
  };
  const s = map[status] || map.present;
  return `<span class="status-badge" style="background:${s.bg};color:${s.color}">${s.label}</span>`;
}

function userStatusBadge(status) {
  const active = status === "active";
  return `<span class="status-badge" style="background:${active ? "#F0FDF4" : "#FFFBEB"};color:${active ? "#10B981" : "#F59E0B"}">${status}</span>`;
}

function imgWithFallback(src, alt, cls, fallbackEmoji) {
  return `<img src="${src}" alt="${alt}" class="${cls}" onerror="this.style.display='none';this.nextElementSibling.style.display='block'" />
          <span style="display:none;font-size:3rem;line-height:5rem;text-align:center;">${fallbackEmoji}</span>`;
}

function makeTabBar(tabs, activeTab, color, id) {
  return `<div class="tab-bar" id="${id}">
    ${tabs
      .map(
        (t) => `
      <button class="tab-btn${t === activeTab ? " active" : ""}" data-tab="${t}" style="${t === activeTab ? `color:${color}` : ""}">
        ${t.charAt(0).toUpperCase() + t.slice(1)}
      </button>`,
      )
      .join("")}
  </div>`;
}

function attachTabBar(barId, contentRenderer, color) {
  const bar = document.getElementById(barId);
  if (!bar) return;
  bar.addEventListener("click", (e) => {
    const btn = e.target.closest(".tab-btn");
    if (!btn) return;
    const tab = btn.dataset.tab;
    bar.querySelectorAll(".tab-btn").forEach((b) => {
      b.classList.remove("active");
      b.style.color = "";
    });
    btn.classList.add("active");
    btn.style.color = color;
    const contentEl = document.getElementById(barId + "-content");
    if (contentEl) {
      contentEl.innerHTML = contentRenderer(tab);
      animateProgressBars();
    }
  });
}

function animateProgressBars() {
  requestAnimationFrame(() => {
    document.querySelectorAll(".progress-bar[data-pct]").forEach((bar) => {
      bar.style.width = bar.dataset.pct + "%";
    });
  });
}

function getEditProfileUrl() {
  const currentPath = window.location.pathname;
  const target = currentPath.endsWith("Profile.php")
    ? "EditProfile.php"
    : "EditProfile.php";
  return new URL(target, window.location.href).href;
}

/* ════════════════════════════════════════
   TEACHER PROFILE
════════════════════════════════════════ */
function renderTeacherContent(tab) {
  try {
    console.log('renderTeacherContent called with tab:', tab); // DEBUG
    // Use actual students from profileData
    const studentsList = profileData?.studentsList || [];
    const students = studentsList.map((student, index) => ({
      name: student.childName || "Unknown",
      age: student.dateOfBirth ? new Date().getFullYear() - new Date(student.dateOfBirth).getFullYear() : 0,
      status: "present",
      emoji: student.gender === 'M' ? "👦" : "👧"
    }));

    // If no students, show empty state
    if (students.length === 0) {
      students.push(
        { name: "No students enrolled", age: 0, status: "absent", emoji: "📋" }
      );
    }

  const schedule = [
    {
      time: "7:30 – 8:00",
      activity: "Morning Arrival & Free Play",
      icon: "🌅",
    },
    {
      time: "8:00 – 8:30",
      activity: "Circle Time & Morning Meeting",
      icon: "⭕",
    },
    { time: "8:30 – 9:30", activity: "Literacy & Language Arts", icon: "📖" },
    { time: "9:30 – 10:00", activity: "Snack Time", icon: "🍎" },
    {
      time: "10:00 – 11:00",
      activity: "Mathematics & STEM Activities",
      icon: "🔢",
    },
    {
      time: "11:00 – 12:00",
      activity: "Outdoor Play & Physical Education",
      icon: "⚽",
    },
    { time: "12:00 – 12:30", activity: "Lunch Time", icon: "🍱" },
    { time: "12:30 – 2:00", activity: "Creative Arts & Crafts", icon: "🎨" },
    { time: "2:00 – 3:00", activity: "Story Time & Nap / Rest", icon: "📚" },
    {
      time: "3:00 – 3:30",
      activity: "Departure & Parent Handover",
      icon: "🏠",
    },
  ];

  const messages = [
    {
      from: "Emma's Parent",
      text: "Will Emma need anything extra for the nature walk tomorrow?",
      time: "2h ago",
      unread: true,
    },
    {
      from: "Noah's Mom",
      text: "Noah had a great day! He loved the painting activity.",
      time: "5h ago",
      unread: false,
    },
    {
      from: "Principal Collins",
      text: "Reminder: Staff meeting on Friday at 3:30 PM.",
      time: "1d ago",
      unread: true,
    },
    {
      from: "Liam's Dad",
      text: "Liam will be late tomorrow, arriving around 9 AM.",
      time: "1d ago",
      unread: false,
    },
  ];

  const overviewCards = [
    {
      label: "Total Students",
      value: students.length === 0 ? 0 : studentsList.length,
      icon: "👦",
      color: "#1565C0",
      bg: "#EFF6FF",
    },
    {
      label: "Present Today",
      value: 19,
      icon: "✅",
      color: "#10B981",
      bg: "#F0FDF4",
    },
    {
      label: "Absent Today",
      value: 2,
      icon: "❌",
      color: "#EF4444",
      bg: "#FEF2F2",
    },
    {
      label: "Late Today",
      value: 1,
      icon: "⏰",
      color: "#F59E0B",
      bg: "#FFFBEB",
    },
  ];

  if (tab === "overview") {
    return `<div class="overview-grid">
      ${overviewCards
        .map(
          (c) => `
        <div class="stat-tile" style="background:${c.bg}">
          <div class="stat-tile-emoji">${c.icon}</div>
          <div>
            <div class="stat-tile-num" style="color:${c.color}">${c.value}</div>
            <div class="stat-tile-label">${c.label}</div>
          </div>
        </div>`,
        )
        .join("")}
      <div class="card about-card" style="padding:1.25rem">
        <h4 style="color:#374151;margin-bottom:0.75rem">About Me</h4>
        <p>Passionate early childhood educator with 12 years of experience nurturing young learners. I specialize in play-based learning, creative arts integration, and building a warm, inclusive classroom community where every child thrives.</p>
      </div>
    </div>`;
  }

  if (tab === "schedule") {
    return `<div class="schedule-card">
      <div class="schedule-header" style="background:#EFF6FF">
        <h4 style="color:#1565C0">📅 Daily Schedule – Monday</h4>
      </div>
      ${schedule
        .map(
          (s) => `
        <div class="schedule-row">
          <span class="sched-icon">${s.icon}</span>
          <span class="sched-time">${s.time}</span>
          <span class="sched-activity">${s.activity}</span>
        </div>`,
        )
        .join("")}
    </div>`;
  }

  if (tab === "students") {
    return `<div class="student-grid">
      ${students
        .map(
          (s) => `
        <div class="student-card">
          <span class="student-emoji">${s.emoji}</span>
          <div class="flex-1">
            <div class="student-name">${s.name}</div>
            <div class="student-meta">Age ${s.age} · KG1</div>
          </div>
          ${statusBadge(s.status)}
        </div>`,
        )
        .join("")}
    </div>`;
  }

  if (tab === "messages") {
    return `<div class="message-list">
      ${messages
        .map(
          (m) => `
        <div class="message-item${m.unread ? " unread" : ""}">
          <div class="msg-avatar">${m.from.charAt(0)}</div>
          <div class="flex-1">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.25rem">
              <span class="msg-from">${m.from}</span>
              <span class="msg-time">${m.time}</span>
            </div>
            <p class="msg-text">${m.text}</p>
          </div>
          ${m.unread ? '<div class="msg-unread-dot"></div>' : ""}
        </div>`,
        )
        .join("")}
    </div>`;
  }
    console.log('renderTeacherContent - no tab matched, returning empty'); // DEBUG
    return "";
  } catch (error) {
    console.error('ERROR in renderTeacherContent:', error); // DEBUG
    console.error('Stack:', error.stack); // DEBUG
    return `<div style="color: red; padding: 20px;"><h2>Error rendering content</h2><p>${error.message}</p></div>`;
  }
}

function renderTeacher() {
  try {
    console.log('renderTeacher CALLED'); // DEBUG
    const tabs = ["overview", "schedule", "students", "messages"];
    const defaultTab = "overview";
    
    // Get teacher data from profileData
    const teacherData = profileData?.teacherData || {};
    const studentsList = profileData?.studentsList || [];
    
    console.log('renderTeacher - teacherData:', teacherData); // DEBUG
    console.log('renderTeacher - studentsList:', studentsList); // DEBUG
    
    const firstName = teacherData.firstname || "Teacher";
    const lastName = teacherData.Lastname || "";
    const displayName = firstName + (lastName ? " " + lastName : "");
    const specialization = teacherData.specialization || "Teacher";
    const experience = teacherData.exprience || 0;
    const email = teacherData.email || "";
    const childrenCount = studentsList.length;
    
    console.log('renderTeacher - displayName:', displayName); // DEBUG

    const html = `<div class="profile-layout">
    <!-- LEFT -->
    <div class="left-panel">
      <div class="profile-card">
        <div class="profile-banner" style="background:linear-gradient(135deg,#1565C0,#1976D2)">
          <span class="profile-banner-emoji">🎓</span>
        </div>
        <div class="profile-body">
          <div class="avatar">
          </div>
          <h2 style="color:#1f2937">${displayName}</h2>
          <div class="role-label" style="color:#1565C0">${specialization} Teacher</div>
          <div class="star-row">${stars(5)} <span class="star-score">4.9/5.0</span></div>
          <div class="info-list">
            <div class="info-item" style="color:#1565C0"><span class="info-icon">📖</span> ${specialization}</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">👥</span> ${childrenCount} Students</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">🏆</span> ${experience} Years Experience</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">✉️</span> ${email}</div>
          </div>
          <button class="btn-primary" style="background:#1565C0" onclick="window.location.href = getEditProfileUrl()">Edit Profile</button>
        </div>
      </div>

      <div class="stats-card">
        <h4>Quick Stats</h4>
        ${[
          { label: "Attendance Rate", value: "94%", color: "#10B981" },
          { label: "Avg. Class Rating", value: "4.9", color: "#F59E0B" },
        ]
          .map(
            (s) => `
          <div class="stat-row">
            <span class="stat-label">${s.label}</span>
            <span class="stat-val" style="color:${s.color}">${s.value}</span>
          </div>`,
          )
          .join("")}
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
      ${makeTabBar(tabs, defaultTab, "#1565C0", "teacherTabs")}
      <div id="teacherTabs-content">${renderTeacherContent(defaultTab)}</div>
    </div>
  </div>`;
    
    console.log('renderTeacher - HTML length:', html.length); // DEBUG
    console.log('renderTeacher - returning HTML'); // DEBUG
    return html;
  } catch (error) {
    console.error('ERROR in renderTeacher:', error); // DEBUG
    console.error('Stack:', error.stack); // DEBUG
    return `<div style="color: red; padding: 20px;"><h2>Error rendering teacher profile</h2><p>${error.message}</p></div>`;
  }
}

/* ════════════════════════════════════════
   ADMIN PROFILE
════════════════════════════════════════ */
function renderAdminContent(tab) {
  const defaultAdminUsers = [
    {
      id: 1,
      first: "Emily",
      last: "Watson",
      name: "Ms. Emily Watson",
      role: "teacher",
      cls: "KG1",
      status: "active",
      email: "emily@wellucation.edu",
      emoji: "👩‍🏫",
    },
    {
      id: 2,
      first: "James",
      last: "Rivera",
      name: "Mr. James Rivera",
      role: "teacher",
      cls: "Arts",
      status: "active",
      email: "james@wellucation.edu",
      emoji: "🎨",
    },
    {
      id: 3,
      first: "Aisha",
      last: "Malik",
      name: "Ms. Aisha Malik",
      role: "teacher",
      cls: "KG2",
      status: "active",
      email: "aisha@wellucation.edu",
      emoji: "👩‍🏫",
    },
    {
      id: 4,
      first: "Mrs.",
      last: "Johnson",
      name: "Mrs. Johnson",
      role: "parent",
      cls: "KG1",
      status: "active",
      email: "johnson@email.com",
      emoji: "👩",
    },
    {
      id: 5,
      first: "Mr.",
      last: "Williams",
      name: "Mr. Williams",
      role: "parent",
      cls: "Nursery",
      status: "pending",
      email: "williams@email.com",
      emoji: "👨",
    },
    {
      id: 6,
      first: "Dr.",
      last: "Lee",
      name: "Dr. Lee",
      role: "admin",
      cls: "All",
      status: "active",
      email: "lee@wellucation.edu",
      emoji: "🛡️",
    },
  ];

  const notifications = [
    {
      icon: "🔔",
      text: "5 new enrollment applications received",
      time: "10 min ago",
      type: "info",
    },
    {
      icon: "⚠️",
      text: "Attendance below 85% for Class Nursery-A this week",
      time: "1 hour ago",
      type: "warning",
    },
    {
      icon: "✅",
      text: "Monthly reports submitted by all teachers",
      time: "2 hours ago",
      type: "success",
    },
    {
      icon: "📅",
      text: "Parent-Teacher meeting scheduled for Dec 15",
      time: "1 day ago",
      type: "info",
    },
    {
      icon: "🏆",
      text: "Wellucation ranked #1 in district quality assessment",
      time: "2 days ago",
      type: "success",
    },
  ];

  const overviewCards = [
    {
      label: "Total Students",
      value: 248,
      icon: "👦",
      color: "#1565C0",
      bg: "#EFF6FF",
    },
    {
      label: "Total Teachers",
      value: 18,
      icon: "👩‍🏫",
      color: "#E91E8C",
      bg: "#FFF0F7",
    },
    {
      label: "Today's Attendance",
      value: "94%",
      icon: "📊",
      color: "#10B981",
      bg: "#F0FDF4",
    },
    {
      label: "Pending Enrollments",
      value: 12,
      icon: "📋",
      color: "#F59E0B",
      bg: "#FFFBEB",
    },
    {
      label: "Active Classes",
      value: 12,
      icon: "🏫",
      color: "#8B5CF6",
      bg: "#F5F3FF",
    },
    {
      label: "Open Alerts",
      value: 3,
      icon: "⚠️",
      color: "#EF4444",
      bg: "#FEF2F2",
    },
  ];

  const typeStyle = {
    success: ["#F0FDF4", "#10B981"],
    warning: ["#FFFBEB", "#F59E0B"],
    info: ["#EFF6FF", "#1565C0"],
  };

  if (tab === "overview") {
    return `<div class="overview-grid">
      ${overviewCards
        .map(
          (c) => `
        <div class="stat-tile" style="background:${c.bg}">
          <div class="stat-tile-emoji">${c.icon}</div>
          <div>
            <div class="stat-tile-num" style="color:${c.color}">${c.value}</div>
            <div class="stat-tile-label">${c.label}</div>
          </div>
        </div>`,
        )
        .join("")}
    </div>`;
  }

  if (tab === "users") {
    const users =
      adminUsersLoaded && adminUsers.length > 0
        ? adminUsers
        : defaultAdminUsers;
    return `<div class="users-table-wrap">
      <div class="users-table-head" style="background:#FFF0F7">
        <h4 style="color:#E91E8C">👥 User Management</h4>
        <button class="btn-add" onclick="openAdminAddUser()">+ Add User</button>
      </div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr><th>User</th><th>Role</th><th>Class</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            ${users
              .map(
                (u) => `
              <tr>
                <td>
                  <div class="td-user">
                    <span style="font-size:1.25rem">${u.emoji || getRoleEmoji(u.role)}</span>
                    <div>
                      <span class="td-name">${u.first && u.last ? `${u.first} ${u.last}` : u.name}</span>
                      <span class="td-meta">${u.email || "No email"}</span>
                    </div>
                  </div>
                </td>
                <td><span class="td-meta">${u.role}</span></td>
                <td><span class="td-meta">${u.cls}</span></td>
                <td>${userStatusBadge(u.status)}</td>
                <td><div class="td-actions"><button class="btn-edit" onclick="editAdminUser(${u.id})">Edit</button><button class="btn-remove" onclick="promptAdminRemoveUser(${u.id})">Remove</button></div></td>
              </tr>`,
              )
              .join("")}
          </tbody>
        </table>
      </div>
    </div>`;
  }

  if (tab === "notifications") {
    return `<div class="notif-list">
      ${notifications
        .map((n) => {
          const [bg, color] = typeStyle[n.type] || typeStyle.info;
          return `<div class="notif-item">
          <span class="notif-icon">${n.icon}</span>
          <div class="flex-1">
            <p class="notif-text">${n.text}</p>
            <p class="notif-time">${n.time}</p>
          </div>
          <span class="notif-type" style="background:${bg};color:${color}">${n.type}</span>
        </div>`;
        })
        .join("")}
    </div>`;
  }

  if (tab === "settings") {
    const fields = [
      { label: "School Name", value: "Wellucation Nursery" },
      { label: "Academic Year", value: "2025 – 2026" },
      { label: "School Email", value: "hello@wellucation.edu" },
      { label: "Max Enrollment", value: "300 Students" },
    ];
    return `<div class="settings-card">
      <h4>⚙️ School Settings</h4>
      ${fields
        .map(
          (f) => `
        <div class="setting-field">
          <label>${f.label}</label>
          <div class="setting-input-row">
            <input value="${f.value}" />
            <button class="btn-edit-field">✏️</button>
          </div>
        </div>`,
        )
        .join("")}
      <div class="toggle-row">
        <div>
          <div class="toggle-title">Parent Notifications</div>
          <div class="toggle-desc">Send daily attendance alerts to parents</div>
        </div>
        <div class="toggle-switch"><div class="toggle-thumb"></div></div>
      </div>
      <button class="btn-save">Save Settings</button>
    </div>`;
  }
  return "";
}

function renderAdmin() {
  const tabs = ["overview", "users", "notifications", "settings"];
  const defaultTab = "overview";
  const permissions = [
    "Manage Users",
    "View All Data",
    "Edit Settings",
    "Send Notifications",
    "Generate Reports",
    "Manage Billing",
  ];

  return `<div class="profile-layout">
    <!-- LEFT -->
    <div class="left-panel">
      <div class="profile-card">
        <div class="profile-banner" style="background:linear-gradient(135deg,#E91E8C,#C2185B)">
          <span class="profile-banner-emoji">🛡️</span>
        </div>
        <div class="profile-body">
          <div class="avatar">
            ${imgWithFallback(IMAGES.staffImg2, "Admin", "", "👩‍💼")}
          </div>
          <h2 style="color:#1f2937">Ms. Sarah Collins</h2>
          <div class="role-label" style="color:#E91E8C">School Principal & Admin</div>
          <span class="badge-pill" style="background:#E91E8C;color:white;margin-top:0.25rem">Full Access</span>
          <div class="info-list">
            <div class="info-item" style="color:#1565C0"><span class="info-icon">🛡️</span> Super Administrator</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">✉️</span> sarah@wellucation.edu</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">📞</span> +1 (555) 123-0001</div>
          </div>
        </div>
      </div>

      <div class="stats-card">
        <h4>Permissions</h4>
        ${permissions
          .map(
            (p) => `
          <div class="perm-item">
            <span class="perm-check">✅</span>
            <span class="perm-label">${p}</span>
          </div>`,
          )
          .join("")}
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
      ${makeTabBar(tabs, defaultTab, "#E91E8C", "adminTabs")}
      <div id="adminTabs-content">${renderAdminContent(defaultTab)}</div>
    </div>
  </div>`;
}

/* ════════════════════════════════════════
   PARENT PROFILE
════════════════════════════════════════ */
function renderParent() {
  const attendance = [
    { date: "1", status: "present" },
    { date: "2", status: "present" },
    { date: "3", status: "weekend" },
    { date: "4", status: "weekend" },
    { date: "5", status: "present" },
    { date: "6", status: "absent" },
    { date: "7", status: "present" },
    { date: "8", status: "present" },
    { date: "9", status: "late" },
    { date: "10", status: "weekend" },
    { date: "11", status: "weekend" },
    { date: "12", status: "present" },
    { date: "13", status: "present" },
    { date: "14", status: "present" },
    { date: "15", status: "present" },
    { date: "16", status: "absent" },
    { date: "17", status: "present" },
    { date: "18", status: "weekend" },
    { date: "19", status: "weekend" },
    { date: "20", status: "present" },
    { date: "21", status: "present" },
    { date: "22", status: "late" },
    { date: "23", status: "present" },
    { date: "24", status: "present" },
    { date: "25", status: "weekend" },
    { date: "26", status: "weekend" },
    { date: "27", status: "present" },
    { date: "28", status: "present" },
    { date: "29", status: "present" },
    { date: "30", status: "present" },
  ];

  const messages = [
    {
      from: "Ms. Emily Watson",
      text: "Emma had a wonderful day today! She completed her art project beautifully.",
      time: "2h ago",
      emoji: "👩‍🏫",
    },
    {
      from: "School Office",
      text: "Reminder: School photos are scheduled for December 18th.",
      time: "1d ago",
      emoji: "🏫",
    },
    {
      from: "Ms. Emily Watson",
      text: "Please remember to bring Emma's permission slip for the nature walk.",
      time: "2d ago",
      emoji: "👩‍🏫",
    },
  ];

  const calDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  return `<div class="profile-layout">
    <!-- LEFT -->
    <div class="left-panel">
      <div class="card" style="padding:1.25rem;margin-bottom:1rem">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem">
          <div class="avatar" style="width:4rem;height:4rem;border-radius:1rem;flex-shrink:0">
            ${imgWithFallback(IMAGES.familyImg, "Parent", "", "👩")}
          </div>
          <div>
            <h2 style="color:#1f2937">Mrs. Johnson</h2>
            <div class="role-label" style="color:#10B981;font-weight:600">Parent / Guardian</div>
            <div style="font-size:0.75rem;color:#9ca3af">Since 2023</div>
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:0.5rem">
          <div class="info-item" style="color:#1565C0;font-size:0.75rem"><span class="info-icon">✉️</span> parent@email.com</div>
          <div class="info-item" style="color:#1565C0;font-size:0.75rem"><span class="info-icon">📞</span> +1 (555) 987-6543</div>
        </div>
      </div>

      <div class="card" style="padding:1.25rem;margin-bottom:1rem">
        <h4 style="color:#374151;margin-bottom:0.75rem;font-size:0.875rem">My Children</h4>
        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;border-radius:1rem;background:#FFF0F7">
          <span style="font-size:2rem">👧</span>
          <div>
            <div style="font-weight:900;font-size:0.875rem;color:#1f2937">Emma Johnson</div>
            <div style="font-size:0.75rem;color:#9ca3af">Age 4 · KG1 – Sunflower</div>
            <span class="badge-pill" style="background:#10B981;color:white;font-size:0.7rem">enrolled</span>
          </div>
        </div>
      </div>

      <div class="stats-card">
        <h4>Attendance Stats</h4>
        ${[
          { label: "Present", value: "24", color: "#10B981" },
          { label: "Absent", value: "2", color: "#EF4444" },
          { label: "Late", value: "2", color: "#F59E0B" },
          { label: "Rate", value: "92%", color: "#1565C0" },
        ]
          .map(
            (s) => `
          <div class="stat-row">
            <span class="stat-label">${s.label}</span>
            <span class="stat-val" style="color:${s.color}">${s.value}</span>
          </div>`,
          )
          .join("")}
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel" style="display:flex;flex-direction:column;gap:1.25rem">
      <div class="calendar-card">
        <h4 class="calendar-title">📅 Emma's Attendance – December 2025</h4>
        <div class="calendar-days-header">
          ${calDays.map((d) => `<div class="cal-day-label">${d}</div>`).join("")}
        </div>
        <div class="calendar-grid">
          ${attendance.map((a) => `<div class="cal-day ${a.status}">${a.date}</div>`).join("")}
        </div>
        <div class="cal-legend">
          ${[
            { label: "Present", color: "#10B981" },
            { label: "Absent", color: "#EF4444" },
            { label: "Late", color: "#F59E0B" },
          ]
            .map(
              (l) => `
            <div class="legend-item">
              <div class="legend-dot" style="background:${l.color}"></div>
              <span class="legend-label">${l.label}</span>
            </div>`,
            )
            .join("")}
        </div>
      </div>

      <div class="parent-msg-wrap">
        <div class="parent-msg-head">
          <h4 class="parent-msg-title">💌 Messages from Teacher</h4>
          <span class="new-badge">2 New</span>
        </div>
        ${messages
          .map(
            (m) => `
          <div class="parent-msg-item">
            <span class="pmsg-emoji">${m.emoji}</span>
            <div class="flex-1">
              <div style="display:flex;align-items:center;justify-content:space-between">
                <span class="pmsg-from">${m.from}</span>
                <span class="pmsg-time">${m.time}</span>
              </div>
              <p class="pmsg-text">${m.text}</p>
            </div>
          </div>`,
          )
          .join("")}
      </div>
    </div>
  </div>`;
}

/* ════════════════════════════════════════
   CHILD PROFILE
════════════════════════════════════════ */
function renderChild() {
  const activities = [
    {
      date: "Dec 10",
      title: "Finger Painting – Rainbow Theme",
      emoji: "🎨",
      note: "Very focused and creative!",
    },
    {
      date: "Dec 9",
      title: 'Story Time – "The Very Hungry Caterpillar"',
      emoji: "📚",
      note: "Answered 3 comprehension questions correctly.",
    },
    {
      date: "Dec 8",
      title: "Outdoor Nature Walk",
      emoji: "🌿",
      note: "Identified 5 different plants.",
    },
    {
      date: "Dec 7",
      title: "Music & Movement – Nursery Rhymes",
      emoji: "🎵",
      note: "Sang along confidently to all songs.",
    },
  ];

  const progress = [
    { label: "Language & Literacy", value: 82, color: "#E91E8C" },
    { label: "Mathematics & Logic", value: 75, color: "#1565C0" },
    { label: "Physical Development", value: 90, color: "#10B981" },
    { label: "Social & Emotional", value: 88, color: "#F59E0B" },
    { label: "Creative Arts", value: 95, color: "#8B5CF6" },
  ];

  return `<div class="profile-layout">
    <!-- LEFT -->
    <div class="left-panel">
      <div class="child-card">
        <div class="child-card-inner">
          <span class="child-avatar">👧</span>
          <h2 class="child-name">Emma Johnson</h2>
          <div class="child-meta">Age 4 · KG1 Sunflower Class</div>
          <div class="star-row" style="justify-content:center;margin-top:0.5rem">${stars(5)}</div>
          <div class="child-stats">
            ${[
              { label: "Attendance", value: "92%", color: "#10B981" },
              { label: "Activities", value: "24", color: "#E91E8C" },
              { label: "Awards", value: "3", color: "#F59E0B" },
            ]
              .map(
                (s) => `
              <div class="child-stat-box">
                <div class="child-stat-val" style="color:${s.color}">${s.value}</div>
                <div class="child-stat-label">${s.label}</div>
              </div>`,
              )
              .join("")}
          </div>
        </div>
        <div class="child-badges">
          <div class="child-badge-row">
            <span class="badge-emoji">🏆</span>
            <div>
              <div class="badge-name" style="color:#F59E0B">Star Reader Award</div>
              <div class="badge-date">Received Nov 2025</div>
            </div>
          </div>
          <div class="child-badge-row">
            <span class="badge-emoji">🎨</span>
            <div>
              <div class="badge-name" style="color:#E91E8C">Creative Explorer Badge</div>
              <div class="badge-date">Received Oct 2025</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel" style="display:flex;flex-direction:column;gap:1.25rem">
      <div class="progress-section">
        <h4 class="progress-title">📊 Development Progress</h4>
        ${progress
          .map(
            (p) => `
          <div class="progress-item">
            <div class="progress-label-row">
              <span class="progress-label">${p.label}</span>
              <span class="progress-pct" style="color:${p.color}">${p.value}%</span>
            </div>
            <div class="progress-track">
              <div class="progress-bar" data-pct="${p.value}" style="background:linear-gradient(90deg,${p.color}80,${p.color})"></div>
            </div>
          </div>`,
          )
          .join("")}
      </div>

      <div class="activities-section">
        <h4 class="activities-title">⭐ Recent Activities</h4>
        ${activities
          .map(
            (a) => `
          <div class="activity-item">
            <span class="activity-emoji">${a.emoji}</span>
            <div>
              <div class="activity-date">${a.date}</div>
              <div class="activity-name">${a.title}</div>
              <div class="activity-note">"${a.note}"</div>
            </div>
          </div>`,
          )
          .join("")}
      </div>
    </div>
  </div>`;
}

/* ════════════════════════════════════════
   RENDERER MAP & INIT
════════════════════════════════════════ */
const RENDERERS = {
  teacher: renderTeacher,
  admin: renderAdmin,
  parent: renderParent,
  child: renderChild,
};

function TAB_SETUP(role) {
  const normalizedRole = role.toLowerCase();
  if (normalizedRole === "teacher") {
    attachTabBar("teacherTabs", renderTeacherContent, "#1565C0");
  }
  if (normalizedRole === "admin") {
    attachTabBar("adminTabs", renderAdminContent, "#E91E8C");
  }
}

function switchRole(role) {
  try {
    console.log('switchRole called with:', role); // DEBUG
    
    const content = document.getElementById("profileContent");
    if (!content) {
      console.error('profileContent div not found!'); // DEBUG
      return;
    }
    
    // Normalize role to lowercase for RENDERERS lookup
    const normalizedRole = role.toLowerCase();
    console.log('RENDERERS:', RENDERERS); // DEBUG
    console.log('RENDERERS[' + normalizedRole + ']:', RENDERERS[normalizedRole]); // DEBUG
    
    if (!RENDERERS[normalizedRole]) {
      console.error('No renderer found for role:', normalizedRole); // DEBUG
      return;
    }
    
    content.style.opacity = "0";
    content.style.transform = "translateY(16px)";
    content.style.transition = "opacity 0.15s ease, transform 0.15s ease";

    setTimeout(() => {
      try {
        console.log('Calling renderer for role:', normalizedRole); // DEBUG
        const html = RENDERERS[normalizedRole]();
        console.log('Renderer returned HTML length:', html?.length); // DEBUG
        console.log('Setting innerHTML...'); // DEBUG
        
        content.innerHTML = html;
        console.log('innerHTML set successfully'); // DEBUG
        
        content.style.transition = "opacity 0.25s ease, transform 0.25s ease";
        content.style.opacity = "1";
        content.style.transform = "translateY(0)";
        
        console.log('Animation styles set'); // DEBUG

        TAB_SETUP(normalizedRole);
        animateProgressBars();
        
        console.log('switchRole completed successfully'); // DEBUG
      } catch (error) {
        console.error('Error in switchRole timeout:', error); // DEBUG
        console.error('Stack:', error.stack); // DEBUG
        const errorHtml = `<div style="padding: 40px; background: #ffcccc; border: 2px solid red; border-radius: 8px; margin: 20px;">
          <h2 style="color: red; margin: 0 0 10px 0;">Error Rendering Profile</h2>
          <p style="margin: 0; color: #333; font-family: monospace;">${error.message}</p>
        </div>`;
        content.innerHTML = errorHtml;
        content.style.opacity = "1";
        content.style.transform = "translateY(0)";
      }
    }, 150);
  } catch (error) {
    console.error('Error in switchRole:', error); // DEBUG
    console.error('Stack:', error.stack); // DEBUG
  }
}

// ── Load profile data from controller ──
async function loadProfileData() {
  try {
    const response = await fetch('../Controller/ProfileController.php?action=get', {
      method: 'get',
      credentials: 'include'
    });
    
    const data = await response.json();
    console.log('Profile API Response:', data); // DEBUG
    
    if (data.success && data.data) {
      console.log('Loaded teacher data:', data.data.teacherData); // DEBUG
      return data.data;
    } else {
      console.error('Failed to load profile data:', data.message);
      return profileData; // fallback to initial data
    }
  } catch (error) {
    console.error('Error loading profile:', error);
    return profileData; // fallback to initial data
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  try {
    console.log('DOMContentLoaded fired'); // DEBUG
    console.log('Initial profileData:', profileData); // DEBUG
    
    // Load profile data from controller
    const loadedData = await loadProfileData();
    console.log('Loaded data:', loadedData); // DEBUG
    console.log('Current profileData before merge:', profileData); // DEBUG
    
    if (loadedData) {
      Object.assign(profileData, loadedData);
    }
    
    console.log('Final profileData after merge:', profileData); // DEBUG



    // Role tabs
    const roleTabs = document.getElementById("roleTabs");
    const profileRole = window.CURRENT_PROFILE_ROLE || profileData.userRole || "teacher";
    
    console.log('Profile role:', profileRole); // DEBUG
    console.log('RENDERERS keys:', Object.keys(RENDERERS)); // DEBUG

    if (roleTabs && !window.CURRENT_PROFILE_ROLE) {
      roleTabs.addEventListener("click", (e) => {
        const btn = e.target.closest(".role-tab");
        if (!btn) return;
        const role = btn.dataset.role;

        roleTabs
          .querySelectorAll(".role-tab")
          .forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");

        switchRole(role);
      });
    }

    // Initial render using the current logged-in user role
    console.log('Calling switchRole with:', profileRole); // DEBUG
    switchRole(profileRole);
    
    // Safety check - if nothing renders in 2 seconds, show fallback
    setTimeout(() => {
      const content = document.getElementById("profileContent");
      if (content && !content.innerHTML.trim()) {
        console.warn('No content rendered after 2 seconds, showing fallback'); // DEBUG
        content.innerHTML = `<div style="padding: 40px; background: #fff3cd; border: 2px solid #ff6b6b; border-radius: 8px; margin: 20px;">
          <h2 style="color: #ff6b6b; margin: 0 0 10px 0;">Profile Rendering Issue</h2>
          <p style="margin: 10px 0; color: #333;">The profile data loaded successfully, but there was an issue rendering the display.</p>
          <p style="margin: 10px 0; color: #333; font-family: monospace; font-size: 12px;">
            <strong>User:</strong> ${profileData?.teacherData?.firstname || 'Unknown'} ${profileData?.teacherData?.Lastname || ''}<br>
            <strong>Email:</strong> ${profileData?.teacherData?.email || 'Unknown'}<br>
            <strong>Students:</strong> ${profileData?.studentsList?.length || 0}
          </p>
          <p style="margin: 10px 0; color: #666; font-size: 12px;">Please check the browser console for errors.</p>
        </div>`;
        content.style.opacity = "1";
      }
    }, 2000);
    
    try {
      loadAdminUsers();
    } catch (error) {
      console.error('Error in loadAdminUsers:', error);
    }
    
    try {
      initAdminModal();
    } catch (error) {
      console.error('Error in initAdminModal:', error);
    }
  } catch (error) {
    console.error('ERROR in DOMContentLoaded:', error); // DEBUG
    console.error('Stack:', error.stack); // DEBUG
    
    // Show error on page
    const errorDiv = document.createElement('div');
    errorDiv.style.cssText = 'position: fixed; top: 10px; right: 10px; background: #ffcccc; padding: 15px; border: 2px solid red; font-size: 12px; max-width: 350px; z-index: 9999; font-family: monospace;';
    errorDiv.innerHTML = `<strong style="color: red;">ERROR</strong><br>${error.message}<br><br>${error.stack}`;
    document.body.appendChild(errorDiv);
  }
});

/* ════════════════════════════════════════
   NAVBAR — hamburger & dropdown
════════════════════════════════════════ */
(function () {
  const hamburger = document.getElementById("hamburger");
  const mobileMenu = document.getElementById("mobileMenu");
  const iconMenu = hamburger && hamburger.querySelector(".icon-menu");
  const iconClose = hamburger && hamburger.querySelector(".icon-close");
  const moreDropdown = document.getElementById("moreDropdown");
  const moreBtn = document.getElementById("moreBtn");

  // Hamburger toggle
  if (hamburger && mobileMenu) {
    hamburger.addEventListener("click", () => {
      const isOpen = mobileMenu.classList.toggle("open");
      hamburger.setAttribute("aria-expanded", isOpen);
      mobileMenu.setAttribute("aria-hidden", !isOpen);
      if (iconMenu) iconMenu.classList.toggle("hidden", isOpen);
      if (iconClose) iconClose.classList.toggle("hidden", !isOpen);
    });
  }

})();
