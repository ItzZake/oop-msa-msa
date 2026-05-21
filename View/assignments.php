<?php
// Start session and get user role FIRST - before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userRole = $_SESSION['user_role'] ?? null;

// Redirect to login if not authenticated - BEFORE any includes
if (!isset($_SESSION['user_id']) || !$userRole) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Assignment Center</title>
  <link rel="stylesheet" href="css/Assignment.css" />
  <link rel="stylesheet" href="css/Home.css">
</head>
<body>
<?php
include "header.php";
include "navbar.php";
?>

  <!-- ─── HERO ─── -->
  <section class="hero">
    <span class="hero-deco top-right">📚</span>
    <span class="hero-deco bottom-left">✏️</span>
    <div>
      <span class="hero-badge">📚 Assignments</span>
      <h1>Assignment Center</h1>
      <p class="hero-subtitle">
        Create, track, and submit assignments seamlessly. Teachers can assign work,
        parents can monitor progress, and children can explore their tasks —
        all in one colorful place!
      </p>
    </div>
  </section>

  <!-- ─── MAIN CONTENT ─── -->
  <main class="content-section">
    <div class="content-inner" id="view-content">
      <!-- View panel injected by app.js -->
    </div>
  </main>

  <!-- ─── MODAL CONTAINER ─── -->
  <div id="modal-container"></div>

  <!-- ─── APP LOGIC ─── -->
  <script>
    // Pass user role to JavaScript
    const currentUserRole = '<?php echo htmlspecialchars($userRole); ?>';
  </script>
  <script src="scripts/Assignment.js"></script>
</body>
</html>
<?php include "footer.php"?>