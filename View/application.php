<?php
session_start();
$pageTitle = "Application – Wellucation Nursery";
$currentPage = "application";
$pageCss = 'application.css';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';
?>

<section class="page-hero">
  <div class="page-hero__content">
    <h1 class="page-hero__title">Program Application</h1>
    <p class="page-hero__subtitle">Apply for our special programs and activities</p>
  </div>
</section>

<section class="section section--gray">
  <div class="container container-narrow">
    <div class="application-panel">
      <form method="POST" action="submit_application.php" class="application-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="form-group">
          <label class="form-label">Parent Name *</label>
          <input type="text" name="parent_name" required class="form-input" placeholder="John Doe">
        </div>
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" required class="form-input" placeholder="john@example.com">
        </div>
        <div class="form-group">
          <label class="form-label">Child Name *</label>
          <input type="text" name="child_name" required class="form-input" placeholder="Emma Doe">
        </div>
        <div class="form-group">
          <label class="form-label">Program Interest *</label>
          <select name="program" required class="form-select">
            <option value="">Select program</option>
            <option>Music & Art</option>
            <option>Sports & Fitness</option>
            <option>Language Learning</option>
            <option>STEM Activities</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Why are you interested?</label>
          <textarea name="message" class="form-textarea" placeholder="Tell us why your child would benefit..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary application-submit">Submit Application</button>
      </form>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
