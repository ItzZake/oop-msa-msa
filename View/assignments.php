<?php
session_start();
$pageTitle = "Assignments – Wellucation Nursery";
$currentPage = "assignments";
$pageCss = 'assignments.css';
include 'header.php';
include 'navbar.php';
?>

<?php include 'partials/partial_assignments.php'; ?>

<?php include 'footer.php'; ?>
