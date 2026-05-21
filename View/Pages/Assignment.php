<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Assignment Center</title>
  <link rel="stylesheet" href="Assignment.css" />
</head>
<body>

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

  <!-- ─── ROLE TABS ─── -->
  <nav class="role-tabs-bar">
    <div class="role-tabs-inner" id="role-tabs">
      <!-- Buttons injected by app.js -->
    </div>
  </nav>

  <!-- ─── MAIN CONTENT ─── -->
  <main class="content-section">
    <div class="content-inner" id="view-content">
      <!-- View panel injected by app.js -->
    </div>
  </main>

  <!-- ─── MODAL CONTAINER ─── -->
  <div id="modal-container"></div>

  <!-- ─── APP LOGIC ─── -->
  <script src="Assignment.js"></script>

</body>
</html>
