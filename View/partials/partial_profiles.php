<link rel="stylesheet" href="css/profiles.css"/>
<section class="page-hero">
  <div class="page-hero__content">
    <span class="page-badge page-badge--pink">👥 User Profiles</span>
    <h1 class="page-hero__title">Role-Based Profiles</h1>
    <p class="page-hero__subtitle">Select a role below to view the personalized profile experience for each user type.</p>
  </div>
</section>

<div class="view-tabs">
  <div class="view-tabs-inner">
    <button class="view-tab-btn" onclick="setProfileRole('teacher')">🎓 Teacher</button>
    <button class="view-tab-btn" onclick="setProfileRole('admin')">🛡️ Admin</button>
    <button class="view-tab-btn" onclick="setProfileRole('parent')">❤️ Parent</button>
    <button class="view-tab-btn" onclick="setProfileRole('child')">👶 Child</button>
  </div>
</div>

<section class="section section--gray">
  <div class="container">
    <!-- Teacher Profile -->
    <div id="profile-view-teacher" class="profile-view active">
      <div class="profile-layout">
        <aside class="profile-sidebar">
          <div class="profile-card-main">
            <div class="profile-header-bg profile-header-blue"></div>
            <div class="profile-card-body">
              <div class="profile-avatar">👩‍🏫</div>
              <h2 class="profile-name-main">Ms. Emily Watson</h2>
              <div class="profile-role">KG1 Lead Teacher</div>
              <div class="profile-rating">★★★★★ <span class="rating-text">4.9/5.0</span></div>
              <ul class="profile-info">
                <li>📚 Sunflower Class (KG1)</li>
                <li>👥 22 Children</li>
                <li>🏆 12 Years Experience</li>
                <li>✉️ emily@wellucation.edu</li>
              </ul>
              <button class="btn btn-primary btn-block">Edit Profile</button>
            </div>
          </div>
          <div class="profile-stats">
            <h4 class="profile-stats-title">Quick Stats</h4>
            <div class="stat-line"><span class="muted">Attendance Rate</span><span class="strong">94%</span></div>
            <div class="stat-line"><span class="muted">Avg. Class Rating</span><span class="strong">4.9</span></div>
            <div class="stat-line"><span class="muted">Reports Filed</span><span class="strong">22/22</span></div>
          </div>
        </aside>
        <main class="profile-main">
          <div class="stats-grid">
            <div class="stat-card stat-card-blue"><div class="stat-num">22</div><div class="stat-label">Total Students</div></div>
            <div class="stat-card stat-card-green"><div class="stat-num">19</div><div class="stat-label">Present Today</div></div>
            <div class="stat-card stat-card-red"><div class="stat-num">2</div><div class="stat-label">Absent</div></div>
            <div class="stat-card stat-card-yellow"><div class="stat-num">1</div><div class="stat-label">Late Arrival</div></div>
          </div>
          <div class="profile-section">
            <h3 class="section-title">📨 Recent Messages (4)</h3>
            <div class="messages-list">
              <div class="message-item message-item-unread">
                <div class="msg-avatar">E</div>
                <div class="msg-body"><div class="msg-name">Emma's Parent</div><div class="msg-time">2h ago</div><p class="msg-text">Will Emma need anything extra for the nature walk tomorrow?</p></div>
              </div>
              <div class="message-item">
                <div class="msg-avatar">N</div>
                <div class="msg-body"><div class="msg-name">Noah's Mom</div><div class="msg-time">5h ago</div><p class="msg-text">Noah had a great day! He loved the painting activity.</p></div>
              </div>
              <div class="message-item message-item-unread">
                <div class="msg-avatar">P</div>
                <div class="msg-body"><div class="msg-name">Principal Collins</div><div class="msg-time">1d ago</div><p class="msg-text">Reminder: Staff meeting on Friday at 3:30 PM.</p></div>
              </div>
              <div class="message-item">
                <div class="msg-avatar">L</div>
                <div class="msg-body"><div class="msg-name">Liam's Dad</div><div class="msg-time">1d ago</div><p class="msg-text">Liam will be late tomorrow, arriving around 9 AM.</p></div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Parent Profile -->
    <div id="profile-view-parent" class="profile-view is-hidden">
      <div class="profile-layout">
        <aside class="profile-sidebar">
          <div class="profile-card-main">
            <div class="profile-header-bg profile-header-green"></div>
            <div class="profile-card-body">
              <div class="profile-avatar">👩‍👧</div>
              <h2 class="profile-name-main">Sarah Thompson</h2>
              <div class="profile-role">Parent / Guardian</div>
              <ul class="profile-info">
                <li>👧 Emma Thompson, Age 4</li>
                <li>🏫 KG1 – Sunflower Class</li>
                <li>✉️ sarah@example.com</li>
                <li>📞 +1 (555) 234-5678</li>
              </ul>
              <button class="btn btn-primary btn-block">Edit Profile</button>
            </div>
          </div>
          <div class="profile-quick-links">
            <h4 class="profile-stats-title">Quick Links</h4>
            <a href="attendance.php" class="quick-link">📅 View Attendance</a>
            <a href="reports.php" class="quick-link">📊 View Reports</a>
            <a href="assignments.php" class="quick-link">📚 Assignments</a>
            <a href="messages.php" class="quick-link">💬 Message Teacher</a>
          </div>
        </aside>
        <main class="profile-main">
          <div class="profile-section">
            <h3 class="section-title">👧 Emma's Overview</h3>
            <div class="overview-stats">
              <div class="overview-stat"><div class="stat-big">92%</div><div class="stat-label">Attendance</div></div>
              <div class="overview-stat"><div class="stat-big">A</div><div class="stat-label">Academic</div></div>
              <div class="overview-stat"><div class="stat-big">⭐ 3</div><div class="stat-label">Stars This Week</div></div>
            </div>
          </div>
          <div class="profile-section">
            <h3 class="section-title">📬 Recent Updates from School</h3>
            <div class="updates-list">
              <div class="update-item update-item-success"><span class="update-icon">✅</span><div class="update-text"><strong>Emma received a Gold Star for her drawing!</strong><div class="update-time">Ms. Emily Watson · 2h ago</div></div></div>
              <div class="update-item update-item-info"><span class="update-icon">📚</span><div class="update-text"><strong>New assignment: Number Tracing due Dec 13</strong><div class="update-time">System · 1d ago</div></div></div>
              <div class="update-item update-item-warning"><span class="update-icon">📅</span><div class="update-text"><strong>Parent-Teacher meeting scheduled for Dec 15</strong><div class="update-time">Admin · 2d ago</div></div></div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Admin Profile -->
    <div id="profile-view-admin" class="profile-view is-hidden">
      <div class="profile-layout">
        <aside class="profile-sidebar">
          <div class="profile-card-main">
            <div class="profile-header-bg profile-header-pink"></div>
            <div class="profile-card-body">
              <div class="profile-avatar">👩‍💼</div>
              <h2 class="profile-name-main">Ms. Sarah Collins</h2>
              <div class="profile-role">Principal & Founder</div>
              <ul class="profile-info">
                <li>🏫 School Administration</li>
                <li>⏳ 18 Years Experience</li>
                <li>🎓 M.Ed Early Childhood</li>
                <li>✉️ sarah@wellucation.edu</li>
              </ul>
              <button class="btn btn-primary btn-block">Edit Profile</button>
            </div>
          </div>
          <div class="profile-stats">
            <h4 class="profile-stats-title">Admin Stats</h4>
            <div class="stat-line"><span class="muted">Total Students</span><span class="strong">248</span></div>
            <div class="stat-line"><span class="muted">Total Staff</span><span class="strong">18</span></div>
            <div class="stat-line"><span class="muted">Enrollment Rate</span><span class="strong">96%</span></div>
          </div>
        </aside>
        <main class="profile-main">
          <div class="stats-grid">
            <div class="stat-card stat-card-pink"><div class="stat-num">248</div><div class="stat-label">Total Students</div></div>
            <div class="stat-card stat-card-blue"><div class="stat-num">18</div><div class="stat-label">Staff Members</div></div>
            <div class="stat-card stat-card-green"><div class="stat-num">5</div><div class="stat-label">Pending Apps</div></div>
            <div class="stat-card stat-card-yellow"><div class="stat-num">$42k</div><div class="stat-label">Monthly Revenue</div></div>
          </div>
          <div class="profile-section">
            <h3 class="section-title">📋 Pending Actions</h3>
            <div class="actions-list">
              <div class="action-item"><span class="action-icon">📝</span><div class="action-body"><strong>5 enrollment applications awaiting review</strong><div class="action-time">4 hours ago</div></div><button class="btn btn-small">Review</button></div>
              <div class="action-item"><span class="action-icon">💰</span><div class="action-body"><strong>3 overdue fee payments</strong><div class="action-time">1 day ago</div></div><button class="btn btn-small">Send Reminder</button></div>
              <div class="action-item"><span class="action-icon">📅</span><div class="action-body"><strong>Staff performance reviews due</strong><div class="action-time">This week</div></div><button class="btn btn-small">Schedule</button></div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Child Profile -->
    <div id="profile-view-child" class="profile-view is-hidden">
      <div class="child-hero">
        <div class="child-hero-left">
          <div class="child-avatar-large">👧🏼</div>
          <div><h2 class="child-greeting">Hi Emma! 🌟</h2><div class="child-class">KG1 – Sunflower Class</div><div class="child-badges"><span class="badge badge-pink">Age 4</span><span class="badge badge-blue">🏫 Sunflower</span></div></div>
        </div>
        <div class="child-hero-right"><div class="child-stars-badge"><div class="stat-big stat-pink">🏆 3</div><div class="stat-label">Gold Stars</div></div></div>
      </div>
      <div class="profile-section">
        <div class="child-interests">
          <div class="interest-card"><div class="interest-emoji">🎨</div><div class="interest-name">Art & Crafts</div><div class="interest-detail">Favorite subject</div></div>
          <div class="interest-card"><div class="interest-emoji">📚</div><div class="interest-name">Reading</div><div class="interest-detail">5 books this month</div></div>
          <div class="interest-card"><div class="interest-emoji">🎵</div><div class="interest-name">Music</div><div class="interest-detail">Loves singing</div></div>
          <div class="interest-card"><div class="interest-emoji">⚽</div><div class="interest-name">Outdoor Play</div><div class="interest-detail">Active learner</div></div>
        </div>
      </div>
      <div class="profile-section">
        <h3 class="section-title">🏆 My Achievements This Month</h3>
        <div class="achievements-list">
          <div class="achievement-badge"><span class="badge-icon">⭐</span><span class="badge-text">Gold Star Artist</span></div>
          <div class="achievement-badge"><span class="badge-icon">📖</span><span class="badge-text">5 Books Read</span></div>
          <div class="achievement-badge"><span class="badge-icon">🤝</span><span class="badge-text">Best Friend Award</span></div>
          <div class="achievement-badge"><span class="badge-icon">🔢</span><span class="badge-text">Math Superstar</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function setProfileRole(role) {
  document.querySelectorAll('.profile-view').forEach(el => el.classList.add('is-hidden'));
  document.getElementById('profile-view-' + role).classList.remove('is-hidden');
}
</script>
