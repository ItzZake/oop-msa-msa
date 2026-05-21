<?php
// ── Session & Authentication ──
session_start();

// Only keep session data - data will be fetched from controller via AJAX
$userRole = strtolower($_SESSION['user_role'] ?? 'user');
$userId = $_SESSION['user_id'] ?? null;

$roleTitles = [
    'teacher' => 'Teacher Profile',
    'parent' => 'Parent Profile',
    'child' => 'Child Profile',
    'admin' => 'Admin Profile',
];
$heroTitle = $roleTitles[$userRole] ?? 'User Profile';

// Initialize empty data structure - will be populated from controller
$jsProfileData = json_encode([
    'userRole' => $userRole,
    'userId' => $userId,
    'userData' => null,
    'teacherData' => null,
    'parentData' => null,
    'childData' => null,
    'adminData' => null,
    'studentsList' => [],
    'childrenList' => []
]);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Role-Based Profiles</title>
    <link rel="stylesheet" href="css/profiles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap"
      rel="stylesheet"
    />
    <script>
      (function () {
        try {
          var path = location.pathname;
          var baseHref = path.substring(0, path.lastIndexOf("/") + 1) || "/";
          var b = document.createElement("base");
          b.href = baseHref;
          document.head.appendChild(b);
        } catch (e) {
          /* noop */
        }
      })();
    </script>
  </head>
<body>
<?php
include "header.php";
include "navbar.php";
?>
    <!-- HERO HEADER -->
    <section class="hero-header">
      <div class="hero-bg-icon">👤</div>
      <div class="hero-inner animate-fade-up">
        <span class="pill-badge">👤 User Profiles</span>
        <h1 class="hero-title"><?php echo htmlspecialchars($heroTitle); ?></h1>
      </div>
    </section>

    <!-- ROLE SELECTOR -->
    <section class="role-selector-bar">
      <div class="container">
        <div class="role-tabs" id="roleTabs">
          <button
            class="role-tab active"
            data-role="<?php echo htmlspecialchars($userRole); ?>"
            style="--role-color: #1565c0; --role-bg: #eff6ff"
          >
            <?php echo htmlspecialchars($heroTitle); ?>
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

    <script>
      // Pass PHP data to JavaScript
      const profileData = <?php echo $jsProfileData; ?>;
    </script>
    <script src="scripts/Profile.js"></script>
  </body>
</html>

<?php
	include "footer.php";
?>
