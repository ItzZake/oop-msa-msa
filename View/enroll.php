<?php
session_start();
$pageTitle = "Enroll Now – Wellucation Nursery";
$currentPage = "enroll";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo htmlspecialchars($pageTitle); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="css/enroll.css" />
	<link rel="stylesheet" href="css/navbar.css" />
	<link rel="stylesheet" href="css/footer.css" />
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
?>
<main class="page-shell">
	<section class="enroll-hero">
		<div class="container enroll-hero__content text-center">
			<span class="enroll-badge">🌟 Enrollment</span>
			<h1 class="enroll-title">Welcome to Wellucation Nursery</h1>
			<p class="page-hero__context">Apply today and let us support your child's early learning journey with personalised care and joyful learning.</p>
		</div>
	</section>

	<section class="section section--gray">
		<div class="container enroll-grid">
			<div class="enroll-panel">
				<?php if (isset($_SESSION['message'])): ?>
					<div class="contact-status success">
						<span>✅ <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></span>
					</div>
				<?php endif; ?>
				<?php if (isset($_SESSION['error'])): ?>
					<div class="contact-status error">
						<span>⚠️ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
					</div>
				<?php endif; ?>

				<form class="enroll-form" method="POST" action="process_enrollment.php">
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

					<div class="form-card">
						<h2>👤 Parent / Guardian Information</h2>
						<div class="form-grid">
							<div class="field-group">
								<label>Full Name *</label>
								<input type="text" name="parent_name" required class="form-input" placeholder="John Doe">
							</div>
							<div class="field-group">
								<label>Email Address *</label>
								<input type="email" name="parent_email" required class="form-input" placeholder="john@example.com">
							</div>
							<div class="field-group">
								<label>Phone Number *</label>
								<input type="tel" name="parent_phone" required class="form-input" placeholder="+1 (555) 123-4567">
							</div>
							<div class="field-group">
								<label>Home Address *</label>
								<input type="text" name="address" required class="form-input" placeholder="123 Main St, City, State">
							</div>
						</div>
					</div>

					<div class="form-card">
						<h2>👶 Child Information</h2>
						<div class="form-grid">
							<div class="field-group">
								<label>Child's Full Name *</label>
								<input type="text" name="child_name" required class="form-input" placeholder="Emma Doe">
							</div>
							<div class="field-group">
								<label>Date of Birth *</label>
								<input type="date" name="child_dob" required class="form-input">
							</div>
							<div class="field-group">
							<label>Child's Gender *</label>
							<select name="child_gender" required class="form-select">
								<option value="">Choose gender</option>
								<option value="M">Male</option>
								<option value="F">Female</option>
							</select>
						</div>
						<div class="field-group">
							<label>Select Program *</label>
							<select name="program" required class="form-select">
								<option value="">Choose a program</option>
								<option value="Nursery (Ages 2-3)">Nursery (Ages 2-3)</option>
								<option value="Kindergarten 1 (Ages 3-4)">Kindergarten 1 (Ages 3-4)</option>
								<option value="Kindergarten 2 (Ages 4-5)">Kindergarten 2 (Ages 4-5)</option>
							</select>
						</div>
						<div class="field-group">
							<label>Preferred Start Date *</label>
							<input type="date" name="start_date" required class="form-input">
						</div>
					</div>
				</div>
				<div class="form-card">
					<h2>📞 Emergency Contact</h2>
						<div class="form-grid">
							<div class="field-group">
								<label>Emergency Contact Name *</label>
								<input type="text" name="emergency_name" required class="form-input" placeholder="Jane Doe">
							</div>
							<div class="field-group">
								<label>Emergency Phone *</label>
								<input type="tel" name="emergency_phone" required class="form-input" placeholder="+1 (555) 987-6543">
							</div>
						</div>
					</div>

					<div class="form-card">
						<h2>📋 Additional Information</h2>
						<div class="field-group">
							<label>Medical Information / Allergies</label>
							<textarea name="medical_info" class="form-textarea" placeholder="Please list any allergies, medical conditions, or special needs..."></textarea>
						</div>
						<div class="field-group">
							<label>Additional Comments or Questions</label>
							<textarea name="comments" class="form-textarea" placeholder="Tell us anything else you'd like us to know..."></textarea>
						</div>
					</div>

					<button type="submit" class="btn btn-primary enroll-submit">Submit Enrollment Application →</button>
				</form>
			</div>

			<aside class="enroll-aside">
				<div class="aside-card">
					<span class="aside-badge">Why choose Wellucation?</span>
					<h3>Trusted early education for every family</h3>
					<p>Our programs support social, emotional, and academic growth in a joyful, child-centered environment.</p>
					<ul class="benefit-list">
						<li>🌈 Play-based learning with proven outcomes</li>
						<li>🧑‍🏫 Small groups and caring teachers</li>
						<li>🏫 Safe campus with modern facilities</li>
					</ul>
				</div>

				<div class="aside-card aside-accent">
					<h3>How enrollment works</h3>
					<ol class="process-list">
						<li>Fill out the application form.</li>
						<li>Our team contacts you within 24 hours.</li>
						<li>We confirm placement and share next steps.</li>
					</ol>
				</div>

				<div class="aside-card aside-highlight">
					<h3>New family welcome</h3>
					<p>First-time parents are welcome — no existing child profile is required. Complete this form and we’ll guide you through the rest.</p>
				</div>
			</aside>
		</div>
	</section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
