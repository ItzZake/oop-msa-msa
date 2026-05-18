<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo htmlspecialchars($pageTitle ?? 'Wellucation Nursery'); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="styles.css" />
<?php
require_once __DIR__ . '/../Models/Database.php';
try {
    Database::getInstance();
} catch (Exception $e) {
    error_log('DB connect failed: ' . $e->getMessage());
}
if (!empty($pageCss)) {
    echo '<link rel="stylesheet" href="css/' . htmlspecialchars($pageCss, ENT_QUOTES, 'UTF-8') . '" />';
}
?>
</head>
<body>
<!-- TOP BAR -->
<div class="top-bar">
  <div class="container top-bar-inner">
    <div class="top-bar-left">
      <span>📞 +1 (555) 123-4567</span>
      <span>✉️ hello@wellucation.edu</span>
    </div>
    <div class="top-bar-right">
      <span>Follow us:</span>
      <a href="#">f</a><a href="#">@</a><a href="#">𝕏</a><a href="#">▶</a>
    </div>
  </div>
</div>
