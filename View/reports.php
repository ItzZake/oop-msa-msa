<?php
session_start();
$pageTitle = "Reports – Wellucation Nursery";
$currentPage = "reports";
$pageCss = 'reports.css';
include 'header.php';
include 'navbar.php';
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($pageTitle); ?></title>
	<link rel="stylesheet" href="css/home.css" />
	<link rel="stylesheet" href="reports.css" />
	<link rel="stylesheet" href="css/dashboard.css" />
</head>
<?php include 'partials/partial_reports.php'; ?>

<?php include 'footer.php'; ?>
