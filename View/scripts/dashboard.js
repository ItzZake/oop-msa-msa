// Dashboard Navigation Handler
function setDashNav(btn, section) {
    document.querySelectorAll('.sidebar-nav-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const topbar = document.querySelector('.dashboard-topbar h2');
    if (topbar) topbar.textContent = section.charAt(0).toUpperCase() + section.slice(1);
}
/* ════════════════════════════════════════════
   NAVBAR / MOBILE MENU / DROPDOWN
════════════════════════════════════════════ */
(function initNavbar() {
  const hamburger = document.getElementById("hamburger");
  const mobileMenu = document.getElementById("mobileMenu");
  const menuIcon = hamburger?.querySelector(".icon-menu");
  const closeIcon = hamburger?.querySelector(".icon-close");
  const moreBtn = document.getElementById("moreBtn");
  const moreDropdown = document.getElementById("moreDropdown");

  hamburger?.addEventListener("click", () => {
    const isOpen = mobileMenu.classList.toggle("open");
    menuIcon.classList.toggle("hidden", isOpen);
    closeIcon.classList.toggle("hidden", !isOpen);
    hamburger.setAttribute("aria-expanded", isOpen);
    mobileMenu.setAttribute("aria-hidden", !isOpen);
  });

  moreBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    const isOpen = moreDropdown.classList.toggle("open");
    moreBtn.setAttribute("aria-expanded", isOpen);
  });

  document.addEventListener("click", (e) => {
    if (moreDropdown && !moreDropdown.contains(e.target)) {
      moreDropdown.classList.remove("open");
      moreBtn?.setAttribute("aria-expanded", "false");
    }
  });
})();

/* ════════════════════════════════════════════
   SIDEBAR TOGGLE
════════════════════════════════════════════ */
(function initSidebar() {
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("sidebarToggle");
  const sidebarLabel = document.getElementById("sidebarLabel");
  const quickAction = document.getElementById("sidebarQuickAction");
  const items = document.querySelectorAll(".sidebar__item");
  const breadcrumb = document.getElementById("breadcrumb");

  toggleBtn?.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
  });

  items.forEach((btn) => {
    btn.addEventListener("click", () => {
      // Nav highlight
      items.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      // Section switch
      const section = btn.dataset.section;
      document
        .querySelectorAll(".dash-section")
        .forEach((s) => s.classList.remove("active"));
      document.getElementById(`section-${section}`)?.classList.add("active");

      // Breadcrumb
      if (breadcrumb) breadcrumb.textContent = navLabels[section] || section;
    });
  });
})();

/* ════════════════════════════════════════════
   RENDER STUDENTS TABLE
════════════════════════════════════════════ */
(function renderStudentsTable() {
  const tbody = document.getElementById("studentsTableBody");
  if (!tbody) return;

  recentStudents.forEach((s) => {
    const isGood = s.attendance >= 85;
    const barColor = isGood ? "#10B981" : "#EF4444";
    const pillBg = s.status === "active" ? "#F0FDF4" : "#FEF2F2";
    const pillClr = s.status === "active" ? "#10B981" : "#EF4444";
    const pillText = s.status === "active" ? "✅ Active" : "⚠️ Concern";

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>
        <div class="td-student">
          <span class="td-emoji">${s.emoji}</span>
          <span class="td-name">${s.name}</span>
        </div>
      </td>
      <td class="td-class">${s.cls}</td>
      <td>
        <div class="attendance-bar-wrap">
          <div class="attendance-bar-track">
            <div class="attendance-bar-fill" style="width:${s.attendance}%;background:${barColor}"></div>
          </div>
          <span class="attendance-pct" style="color:${barColor}">${s.attendance}%</span>
        </div>
      </td>
      <td>
        <span class="status-pill" style="background:${pillBg};color:${pillClr}">${pillText}</span>
      </td>
    `;
    tbody.appendChild(tr);
  });
})();

/* ════════════════════════════════════════════
   RENDER ALERTS
════════════════════════════════════════════ */
(function renderAlerts() {
  const list = document.getElementById("alertsList");
  const badge = document.getElementById("alertsBadge");
  if (!list) return;

  if (badge) badge.textContent = alerts.length;

  alerts.forEach((a) => {
    const div = document.createElement("div");
    div.className = "alert-item";
    div.innerHTML = `
      <span class="alert-emoji">${a.icon}</span>
      <div>
        <p class="alert-text">${a.text}</p>
        <p class="alert-time">${a.time}</p>
      </div>
    `;
    list.appendChild(div);
  });
})();

/* ════════════════════════════════════════════
   CHARTS  (Chart.js)
════════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", () => {
  /* Shared tooltip style */
  const tooltipStyle = {
    backgroundColor: "#fff",
    borderColor: "#e5e7eb",
    borderWidth: 1,
    titleColor: "#374151",
    bodyColor: "#6b7280",
    padding: 10,
    cornerRadius: 12,
    boxShadow: "0 4px 20px rgba(0,0,0,0.1)",
  };

  const gridColor = "#f3f4f6";

  /* ── Bar chart: Weekly Attendance ── */
  const attendanceCtx = document.getElementById("attendanceChart");
  if (attendanceCtx) {
    new Chart(attendanceCtx, {
      type: "bar",
      data: {
        labels: weeklyAttendance.map((d) => d.day),
        datasets: [
          {
            label: "Present",
            data: weeklyAttendance.map((d) => d.present),
            backgroundColor: "#1565C0",
            borderRadius: 4,
          },
          {
            label: "Absent",
            data: weeklyAttendance.map((d) => d.absent),
            backgroundColor: "#E91E8C",
            borderRadius: 4,
          },
          {
            label: "Late",
            data: weeklyAttendance.map((d) => d.late),
            backgroundColor: "#F59E0B",
            borderRadius: 4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              usePointStyle: true,
              pointStyle: "circle",
              font: { family: "Nunito", size: 11 },
              padding: 16,
            },
          },
          tooltip: tooltipStyle,
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: "Nunito", size: 11 }, color: "#9ca3af" },
            border: { display: false },
          },
          y: {
            grid: { color: gridColor },
            ticks: { font: { family: "Nunito", size: 11 }, color: "#9ca3af" },
            border: { display: false },
          },
        },
        barPercentage: 0.7,
        categoryPercentage: 0.8,
      },
    });
  }

  /* ── Doughnut chart: Program Distribution ── */
  const programCtx = document.getElementById("programChart");
  if (programCtx) {
    new Chart(programCtx, {
      type: "doughnut",
      data: {
        labels: programDist.map((d) => d.name),
        datasets: [
          {
            data: programDist.map((d) => d.value),
            backgroundColor: programDist.map((d) => d.color),
            borderWidth: 0,
            hoverOffset: 6,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: "60%",
        plugins: {
          legend: { display: false },
          tooltip: tooltipStyle,
        },
      },
    });

    // Custom legend
    const legend = document.getElementById("pieLegend");
    if (legend) {
      programDist.forEach((p) => {
        const item = document.createElement("div");
        item.className = "pie-legend-item";
        item.innerHTML = `
          <div class="pie-legend-left">
            <div class="pie-legend-dot" style="background:${p.color}"></div>
            <span class="pie-legend-name">${p.name}</span>
          </div>
          <span class="pie-legend-val" style="color:${p.color}">${p.value}</span>
        `;
        legend.appendChild(item);
      });
    }
  }

  /* ── Line chart: Enrollment Trend ── */
  const enrollmentCtx = document.getElementById("enrollmentChart");
  if (enrollmentCtx) {
    new Chart(enrollmentCtx, {
      type: "line",
      data: {
        labels: monthlyEnrollment.map((d) => d.month),
        datasets: [
          {
            label: "Students",
            data: monthlyEnrollment.map((d) => d.students),
            borderColor: "#E91E8C",
            backgroundColor: "rgba(233,30,140,0.08)",
            borderWidth: 3,
            pointBackgroundColor: "#E91E8C",
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: tooltipStyle,
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { font: { family: "Nunito", size: 11 }, color: "#9ca3af" },
            border: { display: false },
          },
          y: {
            grid: { color: gridColor },
            ticks: { font: { family: "Nunito", size: 11 }, color: "#9ca3af" },
            border: { display: false },
            min: 200,
            max: 260,
          },
        },
      },
    });
  }
});