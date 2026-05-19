<?php
session_start();
$pageTitle = "Profiles – Wellucation Nursery";
$currentPage = "profiles";
$pageCss = 'Profile.css';

include 'header.php';
include 'navbar.php';
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Role-Based Profiles</title>
    <link rel="stylesheet" href="../view/Pages/Profile.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    
    <!-- HERO HEADER -->
    <section class="hero-header">
      <div class="hero-bg-icon">👤</div>
      <div class="hero-inner animate-fade-up">
        <span class="pill-badge">👤 User Profiles</span>
        <h1 class="hero-title">Role-Based Profiles</h1>
        <p class="hero-sub">
          Select a role below to view the personalized profile experience for
          each user type.
        </p>
      </div>
    </section>

    <!-- ROLE SELECTOR -->
    <section class="role-selector-bar">
      <div class="container">
        <div class="role-tabs" id="roleTabs">
          <button
            class="role-tab active"
            data-role="teacher"
            style="--role-color: #1565c0; --role-bg: #eff6ff"
          >
            🎓 Teacher Profile
          </button>
          <button
            class="role-tab"
            data-role="admin"
            style="--role-color: #e91e8c; --role-bg: #fff0f7"
          >
            🛡️ Admin Profile
          </button>
          <button
            class="role-tab"
            data-role="parent"
            style="--role-color: #10b981; --role-bg: #f0fdf4"
          >
            ❤️ Parent Profile
          </button>
          <button
            class="role-tab"
            data-role="child"
            style="--role-color: #f59e0b; --role-bg: #fffbeb"
          >
            👶 Child Profile
          </button>
        </div>
      </div>
    </section>

    <!-- PROFILE CONTENT -->
    <section class="profile-section">
      <div class="container">
        <div id="profileContent"></div>
      </div>
    </section>

    <script src="../view/Pages/Profile.js"></script>
  </body>
</html>

<?php include 'footer.php'; ?>
