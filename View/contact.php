<?php
session_start();
$pageTitle = "Contact Us – Wellucation Nursery";
$currentPage = "contact";
$pageCss = 'contact.css';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';
?>

<section class="contact-hero">
  <div class="contact-hero__icon">📬</div>
  <div class="container contact-hero__content text-center">
    <span class="page-badge contact-badge">📬 Get In Touch</span>
    <h1 class="page-hero__title">We'd Love to Hear from You!</h1>
    <p class="page-hero__subtitle contact-hero-copy">Have questions about enrollment, programs, or our facilities? Our friendly team is here to help every step of the way.</p>
  </div>
</section>

<section class="section section--gray">
  <div class="container">
    <div class="contact-panel-grid">
      <div>
        <h2 class="about-feature-title contact-section-title">Send Us a Message</h2>
        <p class="contact-section-copy">Fill in the form and our team will get back to you within 24 hours.</p>
        <?php if (isset($_SESSION['message'])): ?>
        <div class="contact-status success">
          <div>✅ <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
        <div class="contact-status error">
          <div>❌ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        </div>
        <?php endif; ?>
        <form method="POST" action="contact_submit.php" class="contact-form-card">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <div class="contact-input-row">
            <div><label class="form-label">Full Name *</label><input type="text" name="name" required class="form-input" placeholder="Your full name"></div>
            <div><label class="form-label">Email Address *</label><input type="email" name="email" required class="form-input" placeholder="your@email.com"></div>
          </div>
          <div class="contact-input-row">
            <div><label class="form-label">Phone Number</label><input type="tel" name="phone" class="form-input" placeholder="+1 (555) 000-0000"></div>
            <div><label class="form-label">I am a...</label><select name="user_type" class="form-select"><option>Parent / Guardian</option><option>Teacher Applicant</option><option>Administrator</option><option>Other</option></select></div>
          </div>
          <div><label class="form-label">Subject *</label><input type="text" name="subject" required class="form-input" placeholder="e.g., Enrollment inquiry, School tour..."></div>
          <div><label class="form-label">Message *</label><textarea name="message" required class="form-textarea" placeholder="Tell us how we can help you..."></textarea></div>
          <button type="submit" class="btn btn-primary btn-block">✉️ Send Message</button>
        </form>
      </div>
      <div>
        <div class="contact-map-box">
          <div class="contact-map-icon">📍</div>
          <h3 class="contact-card-title contact-card-title--blue">Wellucation Nursery</h3>
          <p class="contact-card-text">123 Sunshine Lane, Kidstown, CA 90210</p>
        </div>
        <div class="contact-info-card">
          <h3 class="contact-card-title">School Hours</h3>
          <p><strong>Monday – Friday:</strong> 7:00 AM – 6:00 PM</p>
          <p><strong>Saturday:</strong> 8:00 AM – 2:00 PM</p>
          <p><strong>Sunday:</strong> Closed</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
