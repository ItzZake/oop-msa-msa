<section class="page-hero">
  <div class="page-hero__content">
    <span class="page-badge">📊 Reports Center</span>
    <h1 class="page-hero__title">Reports & Analytics</h1>
    <p class="page-hero__subtitle">Generate, view, and download comprehensive reports on attendance, academic progress, behavioral development, and more.</p>
  </div>
</section>

<div class="view-tabs">
  <div class="view-tabs-inner">
    <button class="view-tab-btn active" onclick="setReportTab(this,'overview')">📊 Overview</button>
    <button class="view-tab-btn" onclick="setReportTab(this,'attendance')">📅 Attendance</button>
    <button class="view-tab-btn" onclick="setReportTab(this,'academic')">📚 Academic</button>
    <button class="view-tab-btn" onclick="setReportTab(this,'students')">👦 Students</button>
    <button class="view-tab-btn" onclick="setReportTab(this,'saved')">💾 Saved Reports</button>
  </div>
</div>

<section class="section section--gray">
  <div class="container">
    <!-- Overview Tab -->
    <div id="report-overview" class="report-tab active">
      <div class="report-stats-grid">
        <div class="report-stat-card"><div class="stat-big stat-green">93%</div><div class="stat-label">Avg. Attendance</div><div class="stat-note">+2% vs last month</div></div>
        <div class="report-stat-card"><div class="stat-big stat-blue">81%</div><div class="stat-label">Avg. Academic Score</div><div class="stat-note">+3% vs last month</div></div>
        <div class="report-stat-card"><div class="stat-big stat-pink">36</div><div class="stat-label">Reports Generated</div><div class="stat-note">12 this month</div></div>
        <div class="report-stat-card"><div class="stat-big stat-yellow">4</div><div class="stat-label">Needs Attention</div><div class="stat-note">2 resolved today</div></div>
      </div>
      <h3 class="section-title">Generate a Report</h3>
      <div class="report-gen-grid">
        <div class="report-gen-card"><div class="report-gen-icon">📅</div><div class="report-gen-num">12</div><h4 class="report-gen-title">Attendance Report</h4><p class="report-gen-desc">Daily, weekly, and monthly attendance summaries</p><button onclick="showToast('✅ Attendance report generated!')" class="btn btn-secondary">⬇ Generate PDF</button></div>
        <div class="report-gen-card"><div class="report-gen-icon">📚</div><div class="report-gen-num">8</div><h4 class="report-gen-title">Academic Progress</h4><p class="report-gen-desc">Subject-by-subject performance and growth tracking</p><button onclick="showToast('✅ Academic report generated!')" class="btn btn-secondary">⬇ Generate PDF</button></div>
        <div class="report-gen-card"><div class="report-gen-icon">⭐</div><div class="report-gen-num">6</div><h4 class="report-gen-title">Behavioral Report</h4><p class="report-gen-desc">Social-emotional development and conduct observations</p><button onclick="showToast('✅ Behavioral report generated!')" class="btn btn-secondary">⬇ Generate PDF</button></div>
        <div class="report-gen-card"><div class="report-gen-icon">📋</div><div class="report-gen-num">5</div><h4 class="report-gen-title">Monthly Summary</h4><p class="report-gen-desc">Comprehensive overview for each class and student</p><button onclick="showToast('✅ Monthly summary generated!')" class="btn btn-secondary">⬇ Generate PDF</button></div>
      </div>
    </div>

    <!-- Saved Reports Tab -->
    <div id="report-saved" class="report-tab is-hidden">
      <div class="saved-reports-card">
        <div class="saved-reports-header"><div><h3 class="section-title">Saved Reports</h3><p class="muted">All previously generated reports ready to download</p></div><button class="btn btn-primary">+ New Report</button></div>
        <div class="saved-reports-list">
          <div class="saved-report-item"><div class="report-icon">📄</div><div class="report-info"><div class="report-name">December Attendance Summary</div><div class="report-meta">Dec 10, 2025 · 245 KB</div></div><span class="report-type">PDF</span><span class="report-category">Attendance</span><button class="icon-btn">⬇</button></div>
          <div class="saved-report-item"><div class="report-icon">📄</div><div class="report-info"><div class="report-name">KG1 Academic Progress – Q2</div><div class="report-meta">Dec 8, 2025 · 382 KB</div></div><span class="report-type">PDF</span><span class="report-category">Academic</span><button class="icon-btn">⬇</button></div>
          <div class="saved-report-item"><div class="report-icon">📊</div><div class="report-info"><div class="report-name">Monthly Summary – November</div><div class="report-meta">Dec 1, 2025 · 189 KB</div></div><span class="report-type">Excel</span><span class="report-category">Monthly</span><button class="icon-btn">⬇</button></div>
          <div class="saved-report-item"><div class="report-icon">📄</div><div class="report-info"><div class="report-name">Enrollment Trends 2025</div><div class="report-meta">Nov 20, 2025 · 561 KB</div></div><span class="report-type">PDF</span><span class="report-category">Enrollment</span><button class="icon-btn">⬇</button></div>
        </div>
      </div>
    </div>

    <!-- Placeholder tabs for other reports -->
    <div id="report-attendance" class="report-tab is-hidden"><div class="placeholder-card"><div class="placeholder-emoji">📅</div><h3 class="placeholder-title">Attendance Analytics</h3><p class="placeholder-text">Detailed attendance charts and class-by-class breakdowns available in the full dashboard.</p></div></div>
    <div id="report-academic" class="report-tab is-hidden"><div class="placeholder-card"><div class="placeholder-emoji">📚</div><h3 class="placeholder-title">Academic Performance</h3><p class="placeholder-text">Subject-by-subject analytics, radar charts and class comparisons available in the reports dashboard.</p></div></div>
    <div id="report-students" class="report-tab is-hidden"><div class="placeholder-card"><div class="placeholder-emoji">👦</div><h3 class="placeholder-title">Individual Student Reports</h3><p class="placeholder-text">Detailed performance metrics for each student, including progress trends and recommendations.</p></div></div>
  </div>
</section>

<script>
function setReportTab(btn, tab) {
  document.querySelectorAll('.view-tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.report-tab').forEach(el => el.classList.add('is-hidden'));
  document.getElementById('report-' + tab).classList.remove('is-hidden');
}
</script>
