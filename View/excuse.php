<?php
session_start();
$pageTitle = "Absence Excuse – Wellucation Nursery";
$currentPage = "excuse";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/enroll.css" /> <!-- Reuse form styling from enroll.css -->
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
?>

<section class="section section-hero">
  <div class="container container-wide text-center">
    <h1 class="page-hero__title">Absence Excuse</h1>
    <p class="page-hero__subtitle">Submit a reason for your child's absence</p>
  </div>
</section>

<section class="section section--gray">
  <div class="container container-narrow">
    <div class="page-panel">
      <form method="POST" action="submit_excuse.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        
        <div class="form-group">
          <label class="form-label">Child Name *</label>
          <input type="text" name="child_name" required class="form-input" placeholder="Emma Doe">
        </div>
        
        <div class="form-group">
          <label class="form-label">Absence Date *</label>
          <input type="date" name="absence_date" required class="form-input">
        </div>
        
        <div class="form-group">
          <label class="form-label">Reason for Absence *</label>
          <select name="reason" required class="form-select">
            <option value="">Select reason</option>
            <option>Sick</option>
            <option>Medical Appointment</option>
            <option>Family Event</option>
            <option>Travel</option>
            <option>Other</option>
          </select>
        </div>
        
        <div class="form-group">
          <label class="form-label">Additional Details</label>
          <textarea name="details" class="form-textarea" placeholder="Provide any additional information..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Submit Excuse</button>
      </form>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
