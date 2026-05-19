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
	<title>Enroll Now – Wellucation Nursery</title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="../css/enroll.css"/>
	<link rel="stylesheet" href="../css/navbar.css"/>
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
?>
	
	<section class="enroll-hero">
		<div class="container enroll-hero__content text-center">
			<span class="page-badge enroll-badge">🌟 Enrollment</span>
			<h1 class="page-hero__title">Join the Wellucation Family</h1>
			<p class="page-hero__subtitle">Start your child's journey towards a bright future. Fill out the form below to apply for enrollment.</p>
		</div>
	</section>
	
	<section class="section section--gray">
		<div class="container container-wide">
			<?php if (isset($_SESSION['message'])): ?>
				<div class="contact-status success">
					<div>✅ <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
				</div>
				<?php endif; ?>
				<div class="enroll-panel">
					<form method="POST" action="process_enrollment.php">
						<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
						<div class="enroll-section">
							<h3 class="enroll-section-title">👤 Parent/Guardian Information</h3>
							<div class="enroll-row">
								<div><label class="form-label">Full Name *</label><input type="text" name="parent_name" required class="form-input" placeholder="John Doe"></div>
								<div><label class="form-label">Email Address *</label><input type="email" name="parent_email" required class="form-input" placeholder="john@example.com"></div>
								<div><label class="form-label">Phone Number *</label><input type="tel" name="parent_phone" required class="form-input" placeholder="+1 (555) 123-4567"></div>
								<div><label class="form-label">Home Address *</label><input type="text" name="address" required class="form-input" placeholder="123 Main St, City, State"></div>
							</div>
						</div>
						<div class="enroll-section">
							<h3 class="enroll-section-title">👶 Child Information</h3>
							<div class="enroll-row">
								<div><label class="form-label">Child's Full Name *</label><input type="text" name="child_name" required class="form-input" placeholder="Emma Doe"></div>
								<div><label class="form-label">Date of Birth *</label><input type="date" name="child_dob" required class="form-input"></div>
								<div><label class="form-label">Select Program *</label><select name="program" required class="form-select"><option value="">Choose a program</option><option>Nursery (Ages 2-3)</option><option>Kindergarten 1 (Ages 3-4)</option><option>Kindergarten 2 (Ages 4-5)</option></select></div>
								<div><label class="form-label">Preferred Start Date *</label><input type="date" name="start_date" required class="form-input"></div>
							</div>
						</div>
						<div class="enroll-section">
							<h3 class="enroll-section-title">📞 Emergency Contact</h3>
							<div class="enroll-row">
								<div><label class="form-label">Emergency Contact Name *</label><input type="text" name="emergency_name" required class="form-input" placeholder="Jane Doe"></div>
								<div><label class="form-label">Emergency Phone *</label><input type="tel" name="emergency_phone" required class="form-input" placeholder="+1 (555) 987-6543"></div>
							</div>
						</div>
						<div class="enroll-section">
							<h3 class="enroll-section-title">📋 Additional Information</h3>
							<div class="form-group"><label class="form-label">Medical Information / Allergies</label><textarea name="medical_info" class="form-textarea" placeholder="Please list any allergies, medical conditions, or special needs..."></textarea></div>
							<div class="form-group"><label class="form-label">Additional Comments or Questions</label><textarea name="comments" class="form-textarea" placeholder="Tell us anything else you'd like us to know..."></textarea></div>
						</div>
						<button type="submit" class="btn btn-primary enroll-submit">Submit Enrollment Application →</button>
					</form>
				</div>
			</div>

	</section>

	<?php include 'footer.php'; ?>
	<script src="scripts/enroll-form.js"></script>
</body>
</html>
