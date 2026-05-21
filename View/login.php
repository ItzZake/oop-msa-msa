<?php
session_start();
$pageTitle = "Login – Wellucation Nursery";
$currentPage = "login";
$pageCss = 'login.css';
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Login Wellucation</title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&family=Fredoka+One&display=swap"
		rel="stylesheet" />
	<link rel="stylesheet" href="../view/css/login.css" />
	<link rel="stylesheet" href="../view/css/login_strategy_styles.css" />
</head>

<body>
<?php
include 'header.php';
include 'navbar.php';
?>
	<!-- ══════════════ MAIN LOGIN CONTENT ══════════════ -->
	<main class="login-hero">
		<!-- Decorative corner emojis -->
		<span class="login-hero__deco login-hero__deco--1">⭐</span>
		<span class="login-hero__deco login-hero__deco--2">🌈</span>

<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Login Wellucation</title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&family=Fredoka+One&display=swap"
		rel="stylesheet" />
	<link rel="stylesheet" href="../view/css/login.css" />
</head>

<body>
	<!-- ══════════════ MAIN LOGIN CONTENT ══════════════ -->
	<main class="login-hero">
		<!-- Decorative corner emojis -->
		<span class="login-hero__deco login-hero__deco--1">⭐</span>
		<span class="login-hero__deco login-hero__deco--2">🌈</span>

		<div class="login-hero__inner reveal">
			<h1 class="login-hero__title">Welcome to Wellucation</h1>
			<p class="login-hero__subtitle">Sign in to access your account or create a new one</p>

			<div class="login-card">
				<!-- Tabs -->
				<div class="tabs">
					<button class="tab-btn tab-btn--active" id="tabLogin" data-tab="login">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
							<polyline points="10 17 15 12 10 7" />
							<line x1="15" x2="3" y1="12" y2="12" />
						</svg>
						Login
					</button>
					<button class="tab-btn" id="tabRegister" data-tab="register">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
							<circle cx="9" cy="7" r="4" />
							<line x1="19" x2="19" y1="8" y2="14" />
							<line x1="22" x2="16" y1="11" y2="11" />
						</svg>
						Register
					</button>
				</div>

				<!-- Login Form -->
				<div class="tab-panel tab-panel--active" id="panelLogin">
					<form id="loginForm" novalidate>
=======
		<div class="login-hero__inner reveal">
			<h1 class="login-hero__title">Welcome to Wellucation</h1>
			<p class="login-hero__subtitle">Sign in to access your account or create a new one</p>

			<div class="login-card">
				<!-- Error/Success Messages -->
				<?php if (isset($_SESSION["error"]) && !empty($_SESSION["error"])): ?>
					<div class="alert alert-error" role="alert">
						<?php echo htmlspecialchars($_SESSION["error"]); unset($_SESSION["error"]); ?>
					</div>
				<?php endif; ?>
				
				<?php if (isset($_SESSION["message"]) && !empty($_SESSION["message"])): ?>
					<div class="alert alert-success" role="alert">
						<?php echo htmlspecialchars($_SESSION["message"]); unset($_SESSION["message"]); ?>
					</div>
				<?php endif; ?>

				<!-- Tabs -->
				<div class="tabs">
					<button class="tab-btn tab-btn--active" id="tabLogin" data-tab="login">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
							<polyline points="10 17 15 12 10 7" />
							<line x1="15" x2="3" y1="12" y2="12" />
						</svg>
						Login
					</button>
					<button class="tab-btn" id="tabRegister" data-tab="register">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
							<circle cx="9" cy="7" r="4" />
							<line x1="19" x2="19" y1="8" y2="14" />
							<line x1="22" x2="16" y1="11" y2="11" />
						</svg>
						Register
					</button>
				</div>

				<!-- Login Form -->
				<div class="tab-panel tab-panel--active" id="panelLogin">
					<form id="loginForm" method="POST" action="../Controller/Login.php" novalidate>
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
						<div class="field">
							<label class="field__label" for="loginEmail">Email Address</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="20" height="16" x="2" y="4" rx="2" />
									<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
								</svg>
<<<<<<< HEAD
								<input id="loginEmail" class="field__input" type="email"
=======
								<input id="loginEmail" class="field__input" type="email" name="email"
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
									placeholder="your.email@example.com" required />
							</div>
						</div>

						<div class="field">
							<label class="field__label" for="loginPassword">Password</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
									<path d="M7 11V7a5 5 0 0 1 10 0v4" />
								</svg>
								<input id="loginPassword" class="field__input field__input--padded-right"
<<<<<<< HEAD
									type="password" placeholder="••••••••" required />
=======
									type="password" name="password" placeholder="••••••••" required />
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
								<button type="button" class="field__eye" id="toggleLoginPw"
									aria-label="Toggle password visibility">
									<svg class="eye-icon eye-on" xmlns="http://www.w3.org/2000/svg" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
										<circle cx="12" cy="12" r="3" />
									</svg>
									<svg class="eye-icon eye-off hidden" xmlns="http://www.w3.org/2000/svg" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
										<path
											d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
										<path
											d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
										<line x1="2" x2="22" y1="2" y2="22" />
									</svg>
								</button>
							</div>
						</div>

						<div class="login-meta">
							<label class="checkbox-label">
								<input type="checkbox" class="checkbox" />
								<span>Remember me</span>
							</label>
							<a href="#" class="link-pink">Forgot password?</a>
						</div>

						<button type="submit" class="btn-submit btn-submit--pink">
							Sign In
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
								stroke-linejoin="round">
								<path d="M5 12h14" />
								<path d="m12 5 7 7-7 7" />
							</svg>
						</button>
					</form>
				</div>

				<!-- Register Form -->
				<div class="tab-panel" id="panelRegister">
<<<<<<< HEAD
					<form id="registerForm" novalidate>
						<div class="field">
							<label class="field__label" for="regName">Full Name</label>
=======
					<form id="registerForm" method="POST" action="../Controller/Register.php" novalidate>
						<!-- Account Type Selection -->
						<div class="field">
							<label class="field__label">Account Type <span class="required">*</span></label>
							<div class="account-type-selector">
								<button type="button" class="account-btn account-btn--active" data-role="parent">
									❤️ Parent/Guardian
								</button>
								<button type="button" class="account-btn" data-role="teacher">
									👩‍🏫 Teacher
								</button>
							</div>
							<input type="hidden" name="role" id="selectedRole" value="parent" required />
						</div>

						<div class="field">
							<label class="field__label" for="regFirstName">First Name</label>
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="8" r="5" />
									<path d="M20 21a8 8 0 1 0-16 0" />
								</svg>
<<<<<<< HEAD
								<input id="regName" class="field__input" type="text" placeholder="John Doe" required />
=======
								<input id="regFirstName" class="field__input" type="text" name="firstName" 
									placeholder="John" required />
							</div>
						</div>

						<div class="field">
							<label class="field__label" for="regLastName">Last Name</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="8" r="5" />
									<path d="M20 21a8 8 0 1 0-16 0" />
								</svg>
								<input id="regLastName" class="field__input" type="text" name="lastName" 
									placeholder="Doe" required />
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
							</div>
						</div>

						<div class="field">
							<label class="field__label" for="regEmail">Email Address</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="20" height="16" x="2" y="4" rx="2" />
									<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
								</svg>
<<<<<<< HEAD
								<input id="regEmail" class="field__input" type="email"
=======
								<input id="regEmail" class="field__input" type="email" name="email"
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
									placeholder="your.email@example.com" required />
							</div>
						</div>

<<<<<<< HEAD
=======
						<!-- Parent-Specific Fields -->
						<div id="parentFields" class="role-fields">
							<div class="field">
								<label class="field__label" for="regPhoneNumber">Phone Number</label>
								<div class="field__wrap">
									<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
									</svg>
									<input id="regPhoneNumber" class="field__input" type="tel" name="phone_number"
										placeholder="+1 (555) 000-0000" />
								</div>
							</div>

							<div class="field">
								<label class="field__label" for="regAddress">Address</label>
								<div class="field__wrap">
									<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 10c0 7-7 13-7 13s-7-6-7-13a7 7 0 1 1 14 0z" />
										<circle cx="12" cy="10" r="3" />
									</svg>
									<input id="regAddress" class="field__input" type="text" name="address"
										placeholder="123 Main St, City, State" />
								</div>
							</div>
						</div>

						<!-- Teacher-Specific Fields -->
						<div id="teacherFields" class="role-fields" style="display:none;">
							<div class="field">
								<label class="field__label" for="regQualifications">Qualifications <span class="required">*</span></label>
								<div class="field__wrap">
									<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M22 10v6M2 10l10-5 10 5-10 5z" />
										<path d="M6 12v5c3 3 9 3 12 0v-5" />
									</svg>
									<textarea id="regQualifications" class="field__input" name="qualifications"
										placeholder="e.g., B.S. in Education, TESOL Certification..." required></textarea>
								</div>
							</div>

							<div class="field">
								<label class="field__label" for="regDepartment">Department/Subject Area</label>
								<div class="field__wrap">
									<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
										<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
									</svg>
									<input id="regDepartment" class="field__input" type="text" name="department"
										placeholder="e.g., Mathematics, English, Science" />
								</div>
							</div>
						</div>

>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
						<div class="field">
							<label class="field__label" for="regPassword">Password</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
									<path d="M7 11V7a5 5 0 0 1 10 0v4" />
								</svg>
<<<<<<< HEAD
								<input id="regPassword" class="field__input field__input--padded-right" type="password"
=======
								<input id="regPassword" class="field__input field__input--padded-right" type="password" name="password"
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
									placeholder="••••••••" required />
								<button type="button" class="field__eye" id="toggleRegPw"
									aria-label="Toggle password visibility">
									<svg class="eye-icon eye-on" xmlns="http://www.w3.org/2000/svg" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
										<circle cx="12" cy="12" r="3" />
									</svg>
									<svg class="eye-icon eye-off hidden" xmlns="http://www.w3.org/2000/svg" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
										<path
											d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
										<path
											d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
										<line x1="2" x2="22" y1="2" y2="22" />
									</svg>
								</button>
							</div>
						</div>

						<div class="field">
							<label class="field__label" for="regConfirm">Confirm Password</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
									<path d="M7 11V7a5 5 0 0 1 10 0v4" />
								</svg>
<<<<<<< HEAD
								<input id="regConfirm" class="field__input" type="password" placeholder="••••••••"
									required />
=======
								<input id="regConfirm" class="field__input" type="password" name="confirm_password" 
									placeholder="••••••••" required />
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
							</div>
						</div>

						<div class="field">
							<label class="checkbox-label checkbox-label--start">
								<input type="checkbox" class="checkbox" id="agreeTerms" required />
								<span>
									I agree to the
									<a href="#" class="link-pink">Terms &amp; Conditions</a>
									and
									<a href="#" class="link-pink">Privacy Policy</a>
								</span>
							</label>
						</div>

						<button type="submit" class="btn-submit btn-submit--blue">
							Create Account
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
								stroke-linejoin="round">
								<path d="M5 12h14" />
								<path d="m12 5 7 7-7 7" />
							</svg>
						</button>
					</form>
				</div>

				<div class="login-footer">
					<p>Need help? <a href="#" class="link-pink">Contact Support</a></p>
				</div>
			</div>
		</div>

		<!-- Toast notification -->
		<div id="toast" class="toast" role="alert" aria-live="polite"></div>
	</main>

	<script src="../view/scripts/login.js"></script>
<<<<<<< HEAD
=======
	<script>
		// Strategy Pattern Registration - Role Switching
		document.addEventListener('DOMContentLoaded', function() {
			const accountBtns = document.querySelectorAll('.account-btn');
			const parentFields = document.getElementById('parentFields');
			const teacherFields = document.getElementById('teacherFields');
			const selectedRoleInput = document.getElementById('selectedRole');
			const regQualifications = document.getElementById('regQualifications');

			// Handle account type selection
			accountBtns.forEach(btn => {
				btn.addEventListener('click', function(e) {
					e.preventDefault();
					
					// Remove active class from all buttons
					accountBtns.forEach(b => b.classList.remove('account-btn--active'));
					
					// Add active class to clicked button
					this.classList.add('account-btn--active');
					
					// Get selected role
					const role = this.dataset.role;
					selectedRoleInput.value = role;

					// Show/hide role-specific fields
					if (role === 'parent') {
						parentFields.style.display = 'block';
						teacherFields.style.display = 'none';
						regQualifications.removeAttribute('required');
					} else if (role === 'teacher') {
						parentFields.style.display = 'none';
						teacherFields.style.display = 'block';
						regQualifications.setAttribute('required', 'required');
					}
				});
			});

			// Validate required fields based on role
			const registerForm = document.getElementById('registerForm');
			registerForm.addEventListener('submit', function(e) {
				const role = selectedRoleInput.value;
				
				if (role === 'teacher') {
					// Teacher-specific validation
					if (!regQualifications.value.trim()) {
						e.preventDefault();
						alert('Please provide your qualifications.');
						regQualifications.focus();
						return false;
					}
				}
				
				// Let form submit normally if all validations pass
				return true;
			});
		});
	</script>
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
</body>

</html>

<?php include 'footer.php'; ?>