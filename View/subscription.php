<?php
session_start();
$pageTitle = "Subscription – Wellucation Nursery";
$currentPage = "subscription";
$pageCss = 'subscription.css';
include 'header.php';
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subscription Plans</title>
  <link rel="stylesheet" href="/css/subscription.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- ── Hero ─────────────────────────────────────────────────────── -->
  <section class="hero">
    <div class="hero-blob hero-blob--left"></div>
    <div class="hero-blob hero-blob--right"></div>
    <div class="hero-content animate-in">
      <span class="hero-badge">🎯 Subscription Plans</span>
      <h1 class="hero-title">Choose the Perfect Plan</h1>
      <p class="hero-subtitle">Flexible subscription options designed to meet your family's needs</p>

      <!-- Billing toggle -->
      <div class="billing-toggle">
        <span class="billing-label" id="labelMonthly" data-active="true">Monthly</span>
        <label class="switch" aria-label="Toggle yearly billing">
          <input type="checkbox" id="billingToggle" />
          <span class="switch-track"><span class="switch-thumb"></span></span>
        </label>
        <span class="billing-label" id="labelYearly" data-active="false">Yearly</span>
        <span class="save-badge hidden" id="saveBadge">Save 20%</span>
      </div>
    </div>
  </section>

  <!-- ── Plans ─────────────────────────────────────────────────────── -->
  <section class="plans-section">
    <div class="plans-container">
      <div class="plans-grid" id="plansGrid">

        <!-- Basic -->
        <div class="plan-card animate-in" data-plan="basic">
          <div class="plan-icon-wrap" style="background:#F59E0B15">
            <!-- Star icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <h3 class="plan-name">Basic</h3>
          <p class="plan-desc">Perfect for getting started with quality early education</p>
          <div class="plan-price-wrap">
            <span class="plan-price" style="color:#F59E0B" data-monthly="850" data-yearly="708">$850</span>
            <span class="plan-per">/month</span>
          </div>
          <p class="plan-billed hidden" data-yearly-total="8500">Billed $8,500 annually</p>
          <ul class="plan-features">
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Full-day care (7 AM - 6 PM)</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Balanced meals &amp; snacks</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Play-based learning curriculum</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Monthly progress reports</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Parent-teacher conferences</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Access to outdoor play areas</li>
          </ul>
          <button class="plan-btn plan-btn--outline" style="color:#F59E0B;border-color:#F59E0B">
            Choose Plan
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>

        <!-- Premium (popular) -->
        <div class="plan-card plan-card--popular animate-in" data-plan="premium">
          <div class="popular-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect width="20" height="5" x="2" y="7"/><line x1="12" x2="12" y1="22" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            Most Popular
          </div>
          <div class="plan-icon-wrap" style="background:#E91E8C15">
            <!-- Crown icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/><path d="M5 21h14"/></svg>
          </div>
          <h3 class="plan-name">Premium</h3>
          <p class="plan-desc">Enhanced learning with specialized programs</p>
          <div class="plan-price-wrap">
            <span class="plan-price" style="color:#E91E8C" data-monthly="1200" data-yearly="1000">$1,200</span>
            <span class="plan-per">/month</span>
          </div>
          <p class="plan-billed hidden" data-yearly-total="12000">Billed $12,000 annually</p>
          <ul class="plan-features">
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Everything in Basic</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Music &amp; arts enrichment</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Language development classes</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Swimming lessons (seasonal)</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Weekly progress updates</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Priority enrollment</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Extended hours available</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E91E8C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Field trips included</li>
          </ul>
          <button class="plan-btn plan-btn--filled" style="background:linear-gradient(135deg,#E91E8C,#E91E8CDD)">
            Get Started
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>

        <!-- Elite -->
        <div class="plan-card animate-in" data-plan="elite">
          <div class="plan-icon-wrap" style="background:#8B5CF615">
            <!-- Zap icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <h3 class="plan-name">Elite</h3>
          <p class="plan-desc">Complete premium experience with VIP benefits</p>
          <div class="plan-price-wrap">
            <span class="plan-price" style="color:#8B5CF6" data-monthly="1650" data-yearly="1375">$1,650</span>
            <span class="plan-per">/month</span>
          </div>
          <p class="plan-billed hidden" data-yearly-total="16500">Billed $16,500 annually</p>
          <ul class="plan-features">
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Everything in Premium</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>One-on-one tutoring sessions</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>STEM &amp; coding introduction</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Dance &amp; gymnastics classes</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Daily photo &amp; video updates</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Dedicated family coordinator</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Flexible schedule options</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Summer camp included</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>VIP event access</li>
          </ul>
          <button class="plan-btn plan-btn--outline" style="color:#8B5CF6;border-color:#8B5CF6">
            Choose Plan
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>

      </div>

      <!-- CTA banner -->
      <div class="cta-banner animate-in">
        <h3 class="cta-title">Not sure which plan is right for you?</h3>
        <p class="cta-subtitle">Book a free consultation with our admissions team to find the perfect fit for your family</p>
        <button class="cta-btn">Schedule a Free Consultation</button>
      </div>

    </div>
  </section>

  <!-- Toast -->
  <div id="toast" class="toast hidden">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span id="toastMsg"></span>
  </div>

  <script src="/scripts/subscription.js"></script>
</body>
</html>

</script>
