<?php
session_start();
$pageTitle = "Subscription – Wellucation Nursery";
$currentPage = "subscription";
$pageCss = 'subscription.css';
include 'header.php';
include 'navbar.php';
?>

<section class="page-hero page-hero--has-blobs">
  <div class="page-hero__content">
    <span class="page-badge">🎯 Subscription Plans</span>
    <h1 class="page-hero__title">Choose the Perfect Plan</h1>
    <p class="page-hero__subtitle">Flexible subscription options designed to meet your family's needs.</p>
  </div>
</section>

<div class="billing-toggle-section">
  <div class="billing-toggle-wrap">
    <span id="monthlyLabel" class="billing-label active">Monthly</span>
    <button class="toggle-switch on" id="billingToggle" onclick="toggleBilling()"></button>
    <span id="yearlyLabel" class="billing-label">Yearly</span>
    <span id="saveBadge" class="save-badge is-hidden">Save 20%</span>
  </div>
</div>

<section class="section section--gray">
  <div class="container">
    <div class="plans-grid">
      <div class="plan-card plan-card-basic">
        <div class="plan-icon">⭐</div>
        <h3 class="plan-title">Basic</h3>
        <p class="plan-description">Perfect for getting started with quality early education.</p>
        <div class="plan-price-section"><span class="plan-price plan-price-yellow" id="basic-price">$850</span><span class="plan-period">/month</span></div>
        <ul class="plan-features">
          <li>✓ Full-day program (9 AM – 3 PM)</li>
          <li>✓ Core curriculum activities</li>
          <li>✓ Monthly progress reports</li>
          <li>✓ Parent-teacher meetings (quarterly)</li>
          <li>✓ Nutritious meals included</li>
        </ul>
        <button class="btn btn-secondary btn-block" onclick="selectPlan('basic')">Get Started</button>
      </div>

      <div class="plan-card plan-card-pro plan-popular">
        <div class="plan-badge-popular">POPULAR</div>
        <div class="plan-icon popular-icon">💖</div>
        <h3 class="plan-title plan-title-pink">Professional</h3>
        <p class="plan-description\">Our most popular choice for growing families.</p>
        <div class="plan-price-section\"><span class=\"plan-price plan-price-pink\" id=\"pro-price\">$1,250</span><span class=\"plan-period\">/month</span></div>
        <ul class=\"plan-features\">
          <li>✓ Extended hours (7 AM – 6 PM)</li>
          <li>✓ Enhanced curriculum with arts & music</li>
          <li>✓ Bi-weekly progress reports</li>
          <li>✓ Monthly parent-teacher meetings</li>
          <li>✓ Premium meals & snacks</li>
          <li>✓ Enrichment programs (extra activities)</li>
        </ul>
        <button class=\"btn btn-primary btn-block\" onclick=\"selectPlan('pro')\">Enroll Now</button>
      </div>

      <div class=\"plan-card plan-card-premium\">
        <div class=\"plan-icon\">👑</div>
        <h3 class=\"plan-title\">Premium</h3>
        <p class=\"plan-description\">Complete educational experience with premium services.</p>
        <div class=\"plan-price-section\"><span class=\"plan-price plan-price-green\" id=\"premium-price\">$1,650</span><span class=\"plan-period\">/month</span></div>
        <ul class=\"plan-features\">
          <li>✓ Flexible extended hours</li>
          <li>✓ Premium curriculum + specialized programs</li>
          <li>✓ Weekly progress reports with video updates</li>
          <li>✓ Unlimited parent-teacher access</li>
          <li>✓ Gourmet meals & healthy snacks</li>
          <li>✓ All enrichment programs included</li>
          <li>✓ Personal learning consultant</li>
        </ul>
        <button class=\"btn btn-secondary btn-block\" onclick=\"selectPlan('premium')\">Learn More</button>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>

<script>
function toggleBilling() {
  const btn = document.getElementById('billingToggle');
  const isMonthly = btn.classList.contains('on');
  btn.classList.toggle('on');
  
  if (isMonthly) {
    document.getElementById('monthlyLabel').classList.remove('active');
    document.getElementById('yearlyLabel').classList.add('active');
    document.getElementById('saveBadge').classList.remove('is-hidden');
    document.getElementById('basic-price').textContent = '$8,160';
    document.getElementById('pro-price').textContent = '$12,000';
    document.getElementById('premium-price').textContent = '$15,840';
  } else {
    document.getElementById('monthlyLabel').classList.add('active');
    document.getElementById('yearlyLabel').classList.remove('active');
    document.getElementById('saveBadge').classList.add('is-hidden');
    document.getElementById('basic-price').textContent = '$850';
    document.getElementById('pro-price').textContent = '$1,250';
    document.getElementById('premium-price').textContent = '$1,650';
  }
}

function selectPlan(plan) {
  showToast('✅ Plan selected: ' + plan.toUpperCase() + ' · Redirecting to enrollment...');
}
</script>
