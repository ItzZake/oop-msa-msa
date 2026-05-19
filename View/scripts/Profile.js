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

/* ════════════════════════════════════════
   TEACHER PROFILE
════════════════════════════════════════ */
function renderTeacherContent(tab) {
  const students = [
    { name: "Emma Johnson", age: 4, status: "present", emoji: "👧" },
    { name: "Noah Williams", age: 4, status: "present", emoji: "👦" },
    { name: "Sophia Brown", age: 5, status: "absent", emoji: "👧" },
    { name: "Liam Davis", age: 4, status: "late", emoji: "👦" },
    { name: "Olivia Miller", age: 4, status: "present", emoji: "👧" },
    { name: "Mason Wilson", age: 5, status: "present", emoji: "👦" },
  ];

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
      value: 22,
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
  return "";
}

function renderTeacher() {
  const tabs = ["overview", "schedule", "students", "messages"];
  const defaultTab = "overview";

  return `<div class="profile-layout">
    <!-- LEFT -->
    <div class="left-panel">
      <div class="profile-card">
        <div class="profile-banner" style="background:linear-gradient(135deg,#1565C0,#1976D2)">
          <span class="profile-banner-emoji">🎓</span>
        </div>
        <div class="profile-body">
          <div class="avatar">
            ${imgWithFallback(IMAGES.staffImg1, "Teacher", "", "👩‍🏫")}
          </div>
          <h2 style="color:#1f2937">Ms. Emily Watson</h2>
          <div class="role-label" style="color:#1565C0">KG1 Lead Teacher</div>
          <div class="star-row">${stars(5)} <span class="star-score">4.9/5.0</span></div>
          <div class="info-list">
            <div class="info-item" style="color:#1565C0"><span class="info-icon">📖</span> Sunflower Class (KG1)</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">👥</span> 22 Children</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">🏆</span> 12 Years Experience</div>
            <div class="info-item" style="color:#1565C0"><span class="info-icon">✉️</span> emily@wellucation.edu</div>
          </div>
          <button class="btn-primary" style="background:#1565C0" onclick="location.href='EditProfile.html'">Edit Profile</button>
        </div>
      </div>

      <div class="stats-card">
        <h4>Quick Stats</h4>
        ${[
          { label: "Attendance Rate", value: "94%", color: "#10B981" },
          { label: "Avg. Class Rating", value: "4.9", color: "#F59E0B" },
          { label: "Reports Filed", value: "22/22", color: "#1565C0" },
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
}

/* ════════════════════════════════════════
   ADMIN PROFILE
════════════════════════════════════════ */
function renderAdminContent(tab) {
  const users = [
    {
      name: "Ms. Emily Watson",
      role: "Teacher",
      cls: "KG1",
      status: "active",
      emoji: "👩‍🏫",
    },
    {
      name: "Mr. James Rivera",
      role: "Teacher",
      cls: "Arts",
      status: "active",
      emoji: "🎨",
    },
    {
      name: "Ms. Aisha Malik",
      role: "Teacher",
      cls: "KG2",
      status: "active",
      emoji: "👩‍🏫",
    },
    {
      name: "Mrs. Johnson",
      role: "Parent",
      cls: "KG1",
      status: "active",
      emoji: "👩",
    },
    {
      name: "Mr. Williams",
      role: "Parent",
      cls: "Nursery",
      status: "pending",
      emoji: "👨",
    },
    {
      name: "Dr. Lee",
      role: "Admin",
      cls: "All",
      status: "active",
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
    return `<div class="users-table-wrap">
      <div class="users-table-head" style="background:#FFF0F7">
        <h4 style="color:#E91E8C">👥 User Management</h4>
        <button class="btn-add">+ Add User</button>
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
                <td><div class="td-user"><span style="font-size:1.25rem">${u.emoji}</span><span class="td-name">${u.name}</span></div></td>
                <td><span class="td-meta">${u.role}</span></td>
                <td><span class="td-meta">${u.cls}</span></td>
                <td>${userStatusBadge(u.status)}</td>
                <td><div class="td-actions"><button class="btn-edit">Edit</button><button class="btn-remove">Remove</button></div></td>
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
  if (role === "teacher") {
    attachTabBar("teacherTabs", renderTeacherContent, "#1565C0");
  } else if (role === "admin") {
    attachTabBar("adminTabs", renderAdminContent, "#E91E8C");
  }
}

function switchRole(role) {
  const content = document.getElementById("profileContent");
  content.style.opacity = "0";
  content.style.transform = "translateY(16px)";
  content.style.transition = "opacity 0.15s ease, transform 0.15s ease";

  setTimeout(() => {
    content.innerHTML = RENDERERS[role]();
    content.style.transition = "opacity 0.25s ease, transform 0.25s ease";
    content.style.opacity = "1";
    content.style.transform = "translateY(0)";

    TAB_SETUP(role);
    animateProgressBars();
  }, 150);
}

document.addEventListener("DOMContentLoaded", () => {
  // Role tabs
  const roleTabs = document.getElementById("roleTabs");
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

  // Initial render
  switchRole("teacher");
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

  // More dropdown toggle
  if (moreBtn && moreDropdown) {
    moreBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = moreDropdown.classList.toggle("open");
      moreBtn.setAttribute("aria-expanded", isOpen);
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!moreDropdown.contains(e.target)) {
        moreDropdown.classList.remove("open");
        moreBtn.setAttribute("aria-expanded", "false");
      }
    });

    // Close dropdown on Escape
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        moreDropdown.classList.remove("open");
        moreBtn.setAttribute("aria-expanded", "false");
      }
    });
  }
})();
