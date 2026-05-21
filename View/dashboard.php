<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard – Wellucation</title>
    <link rel="stylesheet" href="../view/css/Dashboard.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap"
      rel="stylesheet"
    />
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  </head>
  <body>
    <!-- ══════════════ TOP BAR ══════════════ -->
    <div class="topbar" id="topbar">
      <div class="container topbar__inner">
        <div class="topbar__left">
          <span class="topbar__item">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="13"
              height="13"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"
              />
            </svg>
            +1 (555) 123-4567
          </span>
          <span class="topbar__item">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="13"
              height="13"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect width="20" height="16" x="2" y="4" rx="2" />
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
            </svg>
            hello@wellucation.edu
          </span>
        </div>
        <div class="topbar__right">
          <span>Follow us:</span>
          <a href="#" class="topbar__social" aria-label="Facebook">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="14"
              height="14"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"
              />
            </svg>
          </a>
          <a href="#" class="topbar__social" aria-label="Instagram">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="14"
              height="14"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
              <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
            </svg>
          </a>
          <a href="#" class="topbar__social" aria-label="Twitter">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="14"
              height="14"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"
              />
            </svg>
          </a>
          <a href="#" class="topbar__social" aria-label="YouTube">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="14"
              height="14"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"
              />
              <polygon points="10 15 15 12 10 9 10 15" />
            </svg>
          </a>
        </div>
      </div>
    </div>

    <!-- ══════════════ NAVBAR ══════════════ -->
    <nav class="navbar" id="navbar">
      <div class="container navbar__inner">
        <a href="Home.html" class="navbar__logo">
          <div class="navbar__logo-img">
            <img
              src="logo.jpeg"
              alt="Wellucation"
              onerror="
                this.style.display = 'none';
                this.parentElement.innerHTML =
                  '<span style=\'font-size:1.5rem\'>🌟</span>';
              "
            />
          </div>
          <div class="navbar__logo-text">
            <span class="navbar__logo-name">Wellucation</span>
            <span class="navbar__logo-tagline">Learn. Play. Grow</span>
          </div>
        </a>

        <div class="navbar__links" id="navLinks">
          <a href="Home.html" class="nav-link">Home</a>
          <a href="About.html" class="nav-link">About Us</a>
          <a href="contact.html" class="nav-link">Contact Us</a>
          <a href="Login.html" class="nav-link">Login</a>
          <div class="nav-dropdown" id="moreDropdown">
            <button
              class="nav-link nav-dropdown__trigger"
              id="moreBtn"
              aria-expanded="false"
              aria-haspopup="true"
            >
              More
              <svg
                class="nav-dropdown__chevron"
                xmlns="http://www.w3.org/2000/svg"
                width="14"
                height="14"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <polyline points="6 9 12 15 18 9" />
              </svg>
            </button>
            <div class="nav-dropdown__menu" id="moreMenu" role="menu">
              <a href="Profiles.html" class="nav-dropdown__item" role="menuitem"
                >👤 Profiles</a
              >
              <a
                href="Dashboard.html"
                class="nav-dropdown__item active"
                role="menuitem"
                >📊 Dashboard</a
              >
              <a
                href="Attendance.html"
                class="nav-dropdown__item"
                role="menuitem"
                >📅 Attendance</a
              >
              <a href="Reports.html" class="nav-dropdown__item" role="menuitem"
                >📋 Reports</a
              >
              <a
                href="Assignments.html"
                class="nav-dropdown__item"
                role="menuitem"
                >📝 Assignments</a
              >
              <div class="nav-dropdown__divider"></div>
              <a href="Payment.html" class="nav-dropdown__item" role="menuitem"
                >💳 Payment</a
              >
              <a
                href="Subscription.html"
                class="nav-dropdown__item"
                role="menuitem"
                >⭐ Subscription</a
              >
              <a href="Excuse.html" class="nav-dropdown__item" role="menuitem"
                >🙋 Excuse</a
              >
              <a href="Messages.html" class="nav-dropdown__item" role="menuitem"
                >💬 Messages</a
              >
              <a
                href="Application.html"
                class="nav-dropdown__item"
                role="menuitem"
                >📄 Application</a
              >
              <a href="Settings.html" class="nav-dropdown__item" role="menuitem"
                >⚙️ Settings</a
              >
            </div>
          </div>
        </div>

        <div class="navbar__right">
          <a href="Enroll.html" class="btn-enroll btn-enroll--desktop"
            >🌟 Enroll Now</a
          >
          <button
            class="hamburger"
            id="hamburger"
            aria-label="Toggle menu"
            aria-expanded="false"
          >
            <svg
              class="icon-menu"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="4" x2="20" y1="12" y2="12" />
              <line x1="4" x2="20" y1="6" y2="6" />
              <line x1="4" x2="20" y1="18" y2="18" />
            </svg>
            <svg
              class="icon-close hidden"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
        <a href="Home.html" class="nav-link">🏠 Home</a>
        <a href="About.html" class="nav-link">ℹ️ About Us</a>
        <a href="contact.html" class="nav-link">📞 Contact Us</a>
        <a href="Profiles.html" class="nav-link">👤 Profiles</a>
        <a href="Dashboard.html" class="nav-link active">📊 Dashboard</a>
        <a href="Attendance.html" class="nav-link">📅 Attendance</a>
        <a href="Reports.html" class="nav-link">📋 Reports</a>
        <a href="Assignments.html" class="nav-link">📝 Assignments</a>
        <a href="Login.html" class="nav-link">🔐 Login</a>
        <a href="Payment.html" class="nav-link">💳 Payment</a>
        <a href="Subscription.html" class="nav-link">⭐ Subscription</a>
        <a href="Excuse.html" class="nav-link">🙋 Excuse</a>
        <a href="Messages.html" class="nav-link">💬 Messages</a>
        <a href="Application.html" class="nav-link">📄 Application</a>
        <a href="Settings.html" class="nav-link">⚙️ Settings</a>
        <a href="Enroll.html" class="btn-enroll btn-enroll--mobile"
          >🌟 Enroll Now</a
        >
      </div>
    </nav>

    <!-- ══════════════ DASHBOARD LAYOUT ══════════════ -->
    <div class="dashboard-wrapper">
      <!-- SIDEBAR -->
      <aside class="sidebar" id="sidebar">
        <div class="sidebar__header">
          <span class="sidebar__label" id="sidebarLabel">Admin Panel</span>
          <button
            class="sidebar__toggle"
            id="sidebarToggle"
            aria-label="Toggle sidebar"
          >
            <svg
              id="toggleIcon"
              xmlns="http://www.w3.org/2000/svg"
              width="14"
              height="14"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <polyline points="15 18 9 12 15 6" />
            </svg>
          </button>
        </div>

        <nav class="sidebar__nav" id="sidebarNav">
          <button class="sidebar__item active" data-section="overview">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span class="sidebar__item-label">Overview</span>
          </button>
          <button class="sidebar__item" data-section="students">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <span class="sidebar__item-label">Students</span>
          </button>
          <button class="sidebar__item" data-section="teachers">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
              <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </svg>
            <span class="sidebar__item-label">Teachers</span>
          </button>
          <button class="sidebar__item" data-section="attendance">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
              <line x1="16" x2="16" y1="2" y2="6" />
              <line x1="8" x2="8" y1="2" y2="6" />
              <line x1="3" x2="21" y1="10" y2="10" />
              <path d="m9 16 2 2 4-4" />
            </svg>
            <span class="sidebar__item-label">Attendance</span>
          </button>
          <button class="sidebar__item" data-section="analytics">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="18" x2="18" y1="20" y2="10" />
              <line x1="12" x2="12" y1="20" y2="4" />
              <line x1="6" x2="6" y1="20" y2="14" />
            </svg>
            <span class="sidebar__item-label">Analytics</span>
          </button>
          <button class="sidebar__item" data-section="reports">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
              />
              <polyline points="14 2 14 8 20 8" />
              <line x1="16" x2="8" y1="13" y2="13" />
              <line x1="16" x2="8" y1="17" y2="17" />
              <polyline points="10 9 9 9 8 9" />
            </svg>
            <span class="sidebar__item-label">Reports</span>
          </button>
          <button class="sidebar__item" data-section="notifications">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
              <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
            </svg>
            <span class="sidebar__item-label">Notifications</span>
          </button>
          <button class="sidebar__item" data-section="settings">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"
              />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <span class="sidebar__item-label">Settings</span>
          </button>
        </nav>

        <div class="sidebar__quick-action" id="sidebarQuickAction">
          <div class="sidebar__qa-title">Quick Action</div>
          <button
            class="sidebar__qa-btn"
            onclick="alert('Add Student clicked!')"
          >
            + Add Student
          </button>
        </div>
      </aside>

      <!-- MAIN -->
      <main class="dash-main">
        <!-- Topbar -->
        <div class="dash-topbar">
          <div class="dash-topbar__left">
            <div class="dash-topbar__breadcrumb" id="breadcrumb">Overview</div>
            <h2 class="dash-topbar__title">Admin Dashboard</h2>
          </div>
          <div class="dash-topbar__right">
            <div class="dash-search-wrap">
              <svg
                class="dash-search-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
              </svg>
              <input class="dash-search" type="text" placeholder="Search..." />
            </div>
            <button class="dash-bell-btn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
              </svg>
              <span class="dash-bell-dot"></span>
            </button>
            <div class="dash-avatar">S</div>
          </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="dash-content" id="dashContent">
          <!-- ── OVERVIEW ── -->
          <section class="dash-section active" id="section-overview">
            <!-- Welcome Banner -->
            <div class="welcome-banner animate-fade-up">
              <div>
                <div class="welcome-banner__title">
                  Good morning, Ms. Sarah! 👋
                </div>
                <div class="welcome-banner__sub">
                  Here's what's happening at Wellucation today.
                </div>
              </div>
              <div class="welcome-banner__emoji">🏫</div>
            </div>

            <!-- Overview Cards -->
            <div class="overview-cards" id="overviewCards">
              <div
                class="ov-card animate-fade-up"
                style="--clr: #1565c0; --bg: #eff6ff; animation-delay: 0.05s"
              >
                <div class="ov-card__top">
                  <div class="ov-card__icon">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                      <circle cx="9" cy="7" r="4" />
                      <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                      <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                  </div>
                  <svg
                    class="ov-card__trend"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#D1D5DB"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                </div>
                <div class="ov-card__value">248</div>
                <div class="ov-card__label">Total Students</div>
                <div class="ov-card__change">+12 this month</div>
              </div>
              <div
                class="ov-card animate-fade-up"
                style="--clr: #e91e8c; --bg: #fff0f7; animation-delay: 0.1s"
              >
                <div class="ov-card__top">
                  <div class="ov-card__icon">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                      <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                  </div>
                  <svg
                    class="ov-card__trend"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#D1D5DB"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                </div>
                <div class="ov-card__value">18</div>
                <div class="ov-card__label">Total Teachers</div>
                <div class="ov-card__change">+1 new hire</div>
              </div>
              <div
                class="ov-card animate-fade-up"
                style="--clr: #10b981; --bg: #f0fdf4; animation-delay: 0.15s"
              >
                <div class="ov-card__top">
                  <div class="ov-card__icon">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                      <line x1="16" x2="16" y1="2" y2="6" />
                      <line x1="8" x2="8" y1="2" y2="6" />
                      <line x1="3" x2="21" y1="10" y2="10" />
                      <path d="m9 16 2 2 4-4" />
                    </svg>
                  </div>
                  <svg
                    class="ov-card__trend"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#D1D5DB"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                </div>
                <div class="ov-card__value">94%</div>
                <div class="ov-card__label">Today's Attendance</div>
                <div class="ov-card__change">234 / 248 present</div>
              </div>
              <div
                class="ov-card animate-fade-up"
                style="--clr: #f59e0b; --bg: #fffbeb; animation-delay: 0.2s"
              >
                <div class="ov-card__top">
                  <div class="ov-card__icon">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path
                        d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"
                      />
                      <path d="M12 9v4" />
                      <path d="M12 17h.01" />
                    </svg>
                  </div>
                  <svg
                    class="ov-card__trend"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#D1D5DB"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                </div>
                <div class="ov-card__value">3</div>
                <div class="ov-card__label">Active Alerts</div>
                <div class="ov-card__change">2 require action</div>
              </div>
              <div
                class="ov-card animate-fade-up"
                style="--clr: #8b5cf6; --bg: #f5f3ff; animation-delay: 0.25s"
              >
                <div class="ov-card__top">
                  <div class="ov-card__icon">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                      <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                    </svg>
                  </div>
                  <svg
                    class="ov-card__trend"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#D1D5DB"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                </div>
                <div class="ov-card__value">12</div>
                <div class="ov-card__label">Active Classes</div>
                <div class="ov-card__change">4 programs running</div>
              </div>
              <div
                class="ov-card animate-fade-up"
                style="--clr: #e91e8c; --bg: #fff0f7; animation-delay: 0.3s"
              >
                <div class="ov-card__top">
                  <div class="ov-card__icon">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                      <polyline points="16 7 22 7 22 13" />
                    </svg>
                  </div>
                  <svg
                    class="ov-card__trend"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#D1D5DB"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" />
                  </svg>
                </div>
                <div class="ov-card__value">96%</div>
                <div class="ov-card__label">Enrollment Rate</div>
                <div class="ov-card__change">vs 91% last year</div>
              </div>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">
              <!-- Bar Chart -->
              <div
                class="chart-card chart-card--wide animate-fade-up"
                style="animation-delay: 0.35s"
              >
                <div class="chart-card__head">
                  <div>
                    <h3 class="chart-card__title">Weekly Attendance</h3>
                    <p class="chart-card__sub">This week's overview</p>
                  </div>
                  <button class="chart-btn chart-btn--blue">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="12"
                      height="12"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <polygon
                        points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"
                      />
                    </svg>
                    Filter
                  </button>
                </div>
                <canvas id="attendanceChart" height="200"></canvas>
              </div>

              <!-- Pie Chart -->
              <div
                class="chart-card animate-fade-up"
                style="animation-delay: 0.4s"
              >
                <div class="chart-card__head">
                  <div>
                    <h3 class="chart-card__title">Students by Program</h3>
                    <p class="chart-card__sub">Current enrollment split</p>
                  </div>
                </div>
                <canvas id="programChart" height="160"></canvas>
                <div class="pie-legend" id="pieLegend"></div>
              </div>
            </div>

            <!-- Enrollment Trend -->
            <div
              class="chart-card chart-card--full animate-fade-up"
              style="animation-delay: 0.45s"
            >
              <div class="chart-card__head">
                <div>
                  <h3 class="chart-card__title">Enrollment Trend 2025</h3>
                  <p class="chart-card__sub">Monthly student count</p>
                </div>
                <button class="chart-btn chart-btn--pink">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="12"
                    height="12"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" x2="12" y1="15" y2="3" />
                  </svg>
                  Export
                </button>
              </div>
              <canvas id="enrollmentChart" height="160"></canvas>
            </div>

            <!-- Bottom Row: Students table + Alerts -->
            <div class="bottom-row">
              <!-- Student Table -->
              <div
                class="students-table-card animate-fade-up"
                style="animation-delay: 0.5s"
              >
                <div class="students-table-card__head">
                  <h3 class="chart-card__title">Recent Students</h3>
                  <button class="btn-add-student">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="12"
                      height="12"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path d="M5 12h14" />
                      <path d="M12 5v14" />
                    </svg>
                    Add Student
                  </button>
                </div>
                <div class="table-scroll">
                  <table class="students-table">
                    <thead>
                      <tr>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Attendance</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id="studentsTableBody"></tbody>
                  </table>
                </div>
              </div>

              <!-- Alerts -->
              <div
                class="alerts-card animate-fade-up"
                style="animation-delay: 0.55s"
              >
                <div class="alerts-card__head">
                  <h3 class="chart-card__title">Alerts</h3>
                  <span class="alerts-badge" id="alertsBadge"></span>
                </div>
                <div class="alerts-list" id="alertsList"></div>

                <div class="quick-actions">
                  <h4 class="quick-actions__title">Quick Actions</h4>
                  <div class="quick-actions__grid">
                    <button
                      class="qa-btn"
                      style="--clr: #1565c0; --bg: #eff6ff"
                    >
                      <span class="qa-btn__emoji">👦</span>Add Student
                    </button>
                    <button
                      class="qa-btn"
                      style="--clr: #e91e8c; --bg: #fff0f7"
                    >
                      <span class="qa-btn__emoji">📄</span>New Report
                    </button>
                    <button
                      class="qa-btn"
                      style="--clr: #10b981; --bg: #f0fdf4"
                    >
                      <span class="qa-btn__emoji">📢</span>Send Notice
                    </button>
                    <button
                      class="qa-btn"
                      style="--clr: #f59e0b; --bg: #fffbeb"
                    >
                      <span class="qa-btn__emoji">📅</span>Schedule
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ── PLACEHOLDER SECTIONS ── -->
          <section
            class="dash-section placeholder-section"
            id="section-students"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">👩‍🎓</div>
              <h2>Students</h2>
              <p>Full student management coming soon.</p>
            </div>
          </section>
          <section
            class="dash-section placeholder-section"
            id="section-teachers"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">🎓</div>
              <h2>Teachers</h2>
              <p>Teacher management coming soon.</p>
            </div>
          </section>
          <section
            class="dash-section placeholder-section"
            id="section-attendance"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">📅</div>
              <h2>Attendance</h2>
              <p>Detailed attendance tracking coming soon.</p>
            </div>
          </section>
          <section
            class="dash-section placeholder-section"
            id="section-analytics"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">📊</div>
              <h2>Analytics</h2>
              <p>Advanced analytics coming soon.</p>
            </div>
          </section>
          <section
            class="dash-section placeholder-section"
            id="section-reports"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">📋</div>
              <h2>Reports</h2>
              <p>Report generation coming soon.</p>
            </div>
          </section>
          <section
            class="dash-section placeholder-section"
            id="section-notifications"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">🔔</div>
              <h2>Notifications</h2>
              <p>Notification center coming soon.</p>
            </div>
          </section>
          <section
            class="dash-section placeholder-section"
            id="section-settings"
          >
            <div class="placeholder-inner">
              <div class="placeholder-emoji">⚙️</div>
              <h2>Settings</h2>
              <p>Settings panel coming soon.</p>
            </div>
          </section>
        </div>
        <!-- /dash-content -->
      </main>
    </div>
    <!-- /dashboard-wrapper -->

    <!-- ══════════════ FOOTER ══════════════ -->
    <footer class="footer">
      <div class="container footer__grid">
        <div class="footer__col">
          <div class="footer__brand">
            <div class="footer__logo-img">
              <img
                src="logo.jpeg"
                alt="Wellucation"
                onerror="
                  this.style.display = 'none';
                  this.parentElement.innerHTML =
                    '<span style=\'font-size:1.4rem\'>🌟</span>';
                "
              />
            </div>
            <div>
              <div class="footer__brand-name">Wellucation</div>
              <div class="footer__brand-tagline">Learn. Play. Grow</div>
            </div>
          </div>
          <p class="footer__desc">
            Nurturing young minds with love, creativity, and excellence in early
            childhood education.
          </p>
          <div class="footer__socials">
            <a href="#" class="footer__social" aria-label="Facebook"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path
                  d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"
                /></svg
            ></a>
            <a href="#" class="footer__social" aria-label="Instagram"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" /></svg
            ></a>
            <a href="#" class="footer__social" aria-label="Twitter"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path
                  d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"
                /></svg
            ></a>
            <a href="#" class="footer__social" aria-label="YouTube"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path
                  d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"
                />
                <polygon points="10 15 15 12 10 9 10 15" /></svg
            ></a>
          </div>
        </div>
        <div class="footer__col">
          <h4 class="footer__heading">Quick Links</h4>
          <ul class="footer__list">
            <li>
              <a href="Home.html" class="footer__link"
                ><span class="footer__arrow">›</span> Home</a
              >
            </li>
            <li>
              <a href="About.html" class="footer__link"
                ><span class="footer__arrow">›</span> About Us</a
              >
            </li>
            <li>
              <a href="Contact.html" class="footer__link"
                ><span class="footer__arrow">›</span> Contact Us</a
              >
            </li>
            <li>
              <a href="Dashboard.html" class="footer__link"
                ><span class="footer__arrow">›</span> Dashboard</a
              >
            </li>
            <li>
              <a href="Attendance.html" class="footer__link"
                ><span class="footer__arrow">›</span> Attendance</a
              >
            </li>
            <li>
              <a href="Reports.html" class="footer__link"
                ><span class="footer__arrow">›</span> Reports</a
              >
            </li>
            <li>
              <a href="Enroll.html" class="footer__link"
                ><span class="footer__arrow">›</span> Enroll Now</a
              >
            </li>
            <li>
              <a href="Login.html" class="footer__link"
                ><span class="footer__arrow">›</span> Login</a
              >
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4 class="footer__heading">Our Programs</h4>
          <ul class="footer__list">
            <li class="footer__item">
              <span class="footer__arrow">›</span> Nursery (Ages 2–3)
            </li>
            <li class="footer__item">
              <span class="footer__arrow">›</span> Kindergarten 1 (Ages 3–4)
            </li>
            <li class="footer__item">
              <span class="footer__arrow">›</span> Kindergarten 2 (Ages 4–5)
            </li>
            <li class="footer__item">
              <span class="footer__arrow">›</span> After School Care
            </li>
            <li class="footer__item">
              <span class="footer__arrow">›</span> Summer Camp
            </li>
            <li class="footer__item">
              <span class="footer__arrow">›</span> Special Needs Support
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4 class="footer__heading">Get In Touch</h4>
          <div class="footer__contact">
            <div class="footer__contact-row">
              <svg
                class="footer__contact-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                <circle cx="12" cy="10" r="3" /></svg
              ><span>123 Sunshine Lane, Kidstown, CA 90210</span>
            </div>
            <div class="footer__contact-row">
              <svg
                class="footer__contact-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"
                /></svg
              ><span>+1 (555) 123-4567</span>
            </div>
            <div class="footer__contact-row">
              <svg
                class="footer__contact-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <rect width="20" height="16" x="2" y="4" rx="2" />
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" /></svg
              ><span>hello@wellucation.edu</span>
            </div>
          </div>
          <div class="footer__hours">
            <p class="footer__hours-label">School Hours</p>
            <p class="footer__hours-main">Mon – Fri: 7:00 AM – 6:00 PM</p>
            <p class="footer__hours-sub">Sat: 8:00 AM – 2:00 PM</p>
          </div>
        </div>
      </div>
      <div class="footer__bottom">
        <div class="container footer__bottom-inner">
          <p class="footer__copy">
            © 2026 Wellucation Nursery. All rights reserved.
          </p>
          <p class="footer__made-with">
            Made with
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="13"
              height="13"
              viewBox="0 0 24 24"
              fill="#E91E8C"
              stroke="#E91E8C"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
              />
            </svg>
            for little learners
          </p>
        </div>
      </div>
    </footer>

    <script src="../view/scripts/Dashboard.js"></script>
  </body>
</html>
