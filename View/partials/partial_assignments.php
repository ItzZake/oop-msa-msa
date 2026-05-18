<section class="page-hero">
  <div class="page-hero__content">
    <span class="page-badge">📚 Assignments</span>
    <h1 class="page-hero__title">Assignment Center</h1>
    <p class="page-hero__subtitle">Create, track, and submit assignments seamlessly. Teachers, parents, and children — all in one place!</p>
  </div>
</section>

<div class="view-tabs">
  <div class="view-tabs-inner">
    <button class="view-tab-btn active" onclick="setAssignView('teacher')">🎓 Teacher View</button>
    <button class="view-tab-btn" onclick="setAssignView('parent')">❤️ Parent View</button>
    <button class="view-tab-btn" onclick="setAssignView('child')">👶 Child View</button>
  </div>
</div>

<section class="section section--gray">
  <div class="container">
    <!-- Teacher View -->
    <div id="assign-view-teacher" class="assign-view active">
      <div class="assignment-stats-grid">
        <div class="stat-card stat-card-blue"><div class="stat-num">6</div><div class="stat-label">Total Assignments</div></div>
        <div class="stat-card stat-card-yellow"><div class="stat-num">3</div><div class="stat-label">Pending Review</div></div>
        <div class="stat-card stat-card-green"><div class="stat-num">2</div><div class="stat-label">Fully Graded</div></div>
        <div class="stat-card stat-card-red"><div class="stat-num">1</div><div class="stat-label">Overdue Items</div></div>
      </div>
      <div class="assignment-toolbar">
        <div class="search-wrap"><span class="search-icon">🔍</span><input placeholder="Search assignments..." class="form-input"></div>
        <button class="btn btn-primary">+ New Assignment</button>
      </div>
      <div class="assignment-grid">
        <div class="assignment-card assignment-card-green"><div class="assignment-category">🎨 Creative Arts</div><h4 class="assignment-title">My Family Drawing</h4><p class="assignment-meta">KG1 – Sunflower · Due Dec 15, 2025</p><div class="assignment-progress"><span class="progress-label">Submitted</span><span class="progress-value">18/22</span><div class="progress-bar"><div class="progress-fill" style="width:82%"></div></div></div></div>
        <div class="assignment-card assignment-card-blue"><div class="assignment-category">🔢 Math</div><h4 class="assignment-title">Number Tracing Worksheet</h4><p class="assignment-meta">KG1 – Sunflower · Due Dec 13, 2025</p><div class="assignment-progress"><span class="progress-label">Submitted</span><span class="progress-value">15/22</span><div class="progress-bar"><div class="progress-fill" style="width:68%"></div></div></div></div>
        <div class="assignment-card assignment-card-pink"><div class="assignment-category">📖 Literacy</div><h4 class="assignment-title">Letter Recognition – A to E</h4><p class="assignment-meta">KG1 – Sunflower · Due Dec 12, 2025</p><div class="assignment-progress"><span class="progress-label">Submitted</span><span class="progress-value">20/22</span><div class="progress-bar"><div class="progress-fill" style="width:91%"></div></div></div></div>
        <div class="assignment-card assignment-card-purple"><div class="assignment-category">🔬 Science</div><h4 class="assignment-title">Seasons Collage</h4><p class="assignment-meta">KG1 – Sunflower · Due Dec 10, 2025</p><div class="assignment-status-badge overdue">⚠️ Overdue</div><div class="assignment-progress"><span class="progress-label">Submitted</span><span class="progress-value">19/22</span><div class="progress-bar"><div class="progress-fill" style="width:86%"></div></div></div></div>
      </div>
    </div>

    <!-- Parent View -->
    <div id="assign-view-parent" class="assign-view is-hidden">
      <div class="parent-child-card">
        <div class="child-profile-compact">👧 <div><h3 class="child-name">Emma Johnson</h3><div class="child-meta">KG1 – Sunflower · Ms. Emily Watson</div></div></div>
        <div class="assignment-summary"><div class="summary-stat">6<br>Total</div><div class="summary-stat">3<br>Pending</div><div class="summary-stat">2<br>Graded</div><div class="summary-stat">1<br>Overdue</div></div>
      </div>
      <div class="parent-assignments-list">
        <div class="parent-assign-item parent-assign-complete"><span class="emoji">🎨</span><div class="assign-info"><div class="assign-head"><h4>My Family Drawing</h4><span class="badge badge-green">Creative Arts</span></div><div class="assign-due\">Due: Dec 15 · KG1 Sunflower</div><div class="assign-comment\">\"Beautiful drawing! Emma did a wonderful job labeling everyone.\"</div></div><div class="assign-grade\"><span class=\"grade-badge\">✅ Graded</span><div class=\"grade-value\">A</div></div></div>
        <div class="parent-assign-item parent-assign-pending"><span class="emoji">🔢</span><div class="assign-info"><div class="assign-head\"><h4>Number Tracing Worksheet</h4><span class="badge badge-blue\">Math</span></div><div class=\"assign-due\">Due: Dec 13 · KG1 Sunflower</div></div><span class="badge badge-yellow\">⏳ Pending</span></div>
        <div class="parent-assign-item parent-assign-overdue\"><span class="emoji\">🔬</span><div class="assign-info\"><div class="assign-head\"><h4>Seasons Collage</h4><span class="badge badge-purple\">Science</span></div><div class="assign-due\">Due: Dec 10 · KG1 Sunflower</div></div><span class=\"badge badge-red\">⚠️ Overdue</span></div>
      </div>
    </div>

    <!-- Child View -->
    <div id=\"assign-view-child\" class=\"assign-view is-hidden\">
      <div class=\"child-hero\">
        <div><h3 class=\"child-greeting\">Hi Emma! 👋</h3><p class=\"child-subtitle\">You have <strong>3</strong> assignments to complete. Let's get started!</p><span class=\"achievement-inline\">🏆 3 Gold Stars this week</span></div>
        <div class=\"child-hero-emoji\">🌟</div>
      </div>
      <div class=\"child-stats-grid\">
        <div class=\"stat-card stat-card-blue\"><div class=\"stat-emoji\">📚</div><div class=\"stat-num\">6</div><div class=\"stat-label\">Total Tasks</div></div>
        <div class=\"stat-card stat-card-yellow\"><div class=\"stat-emoji\">⏳</div><div class=\"stat-num\">4</div><div class=\"stat-label\">To Do</div></div>
        <div class=\"stat-card stat-card-green\"><div class=\"stat-emoji\">✅</div><div class=\"stat-num\">2</div><div class=\"stat-label\">Done</div></div>
        <div class=\"stat-card stat-card-pink\"><div class=\"stat-emoji\">🏆</div><div class=\"stat-num\">3</div><div class=\"stat-label\">Gold Stars</div></div>
      </div>
      <h4 class=\"section-title\"><span class=\"emoji\">📋</span> My Assignments to Do</h4>
      <div class=\"child-assignments-grid\">
        <div class=\"child-assign-card child-assign-card-blue\"><div class=\"assign-emoji\">🔢</div><span class=\"due-badge\">⏰ 3d left</span><h4 class=\"assign-title\">Number Tracing Worksheet</h4><p class=\"assign-due\">Due Dec 13, 2025 · Math</p><button class=\"btn btn-primary btn-block\">🚀 Start Assignment</button></div>
        <div class=\"child-assign-card child-assign-card-purple\"><div class=\"assign-emoji\">🔬</div><span class=\"due-badge overdue\">⚠️ Overdue!</span><h4 class=\"assign-title\">Seasons Collage</h4><p class=\"assign-due\">Due Dec 10, 2025 · Science</p><button class=\"btn btn-primary btn-block\">🚀 Start Assignment</button></div>
      </div>
    </div>
  </div>
</section>

<script>
function setAssignView(view) {
  document.querySelectorAll('.assign-view').forEach(el => el.classList.add('is-hidden'));
  document.getElementById('assign-view-' + view).classList.remove('is-hidden');
}
</script>
