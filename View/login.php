<?php
session_start();
$pageTitle = "Login – Wellucation Nursery";
$currentPage = "login";
$pageCss = 'login.css';
include 'header.php';
include 'navbar.php';
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
						<div class="field">
							<label class="field__label" for="loginEmail">Email Address</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="20" height="16" x="2" y="4" rx="2" />
									<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
								</svg>
								<input id="loginEmail" class="field__input" type="email"
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
									type="password" placeholder="••••••••" required />
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
					<form id="registerForm" novalidate>
						<div class="field">
							<label class="field__label" for="regName">Full Name</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="8" r="5" />
									<path d="M20 21a8 8 0 1 0-16 0" />
								</svg>
								<input id="regName" class="field__input" type="text" placeholder="John Doe" required />
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
								<input id="regEmail" class="field__input" type="email"
									placeholder="your.email@example.com" required />
							</div>
						</div>

						<div class="field">
							<label class="field__label" for="regPassword">Password</label>
							<div class="field__wrap">
								<svg class="field__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
									<path d="M7 11V7a5 5 0 0 1 10 0v4" />
								</svg>
								<input id="regPassword" class="field__input field__input--padded-right" type="password"
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
								<input id="regConfirm" class="field__input" type="password" placeholder="••••••••"
									required />
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
</body>

</html>

<?php include 'footer.php'; ?>