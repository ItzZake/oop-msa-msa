<?php
session_start();
$pageTitle = "Messages – Wellucation Nursery";
$currentPage = "messages";
$pageCss = 'messages.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/messages.css" />
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
include 'partials/partial_messages.php';
include 'footer.php';
?>
</body>
</html>
