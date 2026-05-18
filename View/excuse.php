<?php
session_start();
$pageTitle = "Absence Excuse – Wellucation Nursery";
$currentPage = "excuse";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
