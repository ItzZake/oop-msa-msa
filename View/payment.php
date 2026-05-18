<?php
session_start();
$pageTitle = "Payment – Wellucation Nursery";
$currentPage = "payment";
$pageCss = 'payment.css';
include 'header.php';
include 'navbar.php';
?>

<section class="page-hero page-hero--has-blobs">
  <div class="page-hero__content">
    <span class="page-badge">💳 Payment</span>
    <h1 class="page-hero__title">Secure Payment Processing</h1>
    <p class="page-hero__subtitle">Complete your payment securely with our encrypted payment system.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="hero-grid">
      <div class="payment-form-card">
        <h2 class="payment-section-title">💳 Payment Method</h2>
        <div id="payMethodGrid" class="payment-method-grid">
          <div class="payment-method-option selected" id="pm-card" onclick="selectPayMethod('card')"><div class="payment-method-emoji">💳</div><div class="payment-method-label">Credit Card</div></div>
          <div class="payment-method-option" id="pm-debit" onclick="selectPayMethod('debit')"><div class="payment-method-emoji">🏦</div><div class="payment-method-label">Debit Card</div></div>
          <div class="payment-method-option" id="pm-paypal" onclick="selectPayMethod('paypal')"><div class="payment-method-emoji">🅿️</div><div class="payment-method-label">PayPal</div></div>
        </div>

        <div id="cardForm" class="payment-card-form">
          <form onsubmit="handlePaymentSubmit(event)">
            <div class="form-group"><label class="form-label">Card Number</label><input type="text" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCard(this)"></div>
            <div class="form-group"><label class="form-label">Cardholder Name</label><input type="text" class="form-input" placeholder="John Doe"></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">Expiry Date</label><input type="text" class="form-input" placeholder="MM/YY" maxlength="5"></div>
              <div class="form-group"><label class="form-label">CVV</label><input type="text" class="form-input" placeholder="123" maxlength="3"></div>
            </div>
            <div class="secure-note"><span class="secure-emoji">🔒</span><div class="secure-text">Your payment is secured with 256-bit SSL encryption.</div></div>
            <button type="submit" class="btn btn-primary btn-block">🔒 Pay Securely</button>
          </form>
        </div>

        <div id="paypalForm" class="payment-paypal is-hidden">
          <div class="paypal-box">
            <div class="paypal-emoji">🅿️</div>
            <p class="paypal-text">You will be redirected to PayPal to complete your payment securely.</p>
            <button onclick="showToast('✅ Redirecting to PayPal...')" class="btn btn-secondary">Continue with PayPal →</button>
          </div>
        </div>
      </div>

      <aside class="order-summary-card">
        <h3 class="order-summary-title">📋 Order Summary</h3>
        <div class="order-summary-lines">
          <div class="order-line"><span class="muted">Monthly Tuition</span><span class="strong">$850.00</span></div>
          <div class="order-line"><span class="muted">Registration Fee</span><span class="strong">$150.00</span></div>
          <div class="order-line"><span class="muted">Activity Materials</span><span class="strong">$50.00</span></div>
          <div class="order-line"><span class="muted">Early Bird Discount</span><span class="strong discount">−$100.00</span></div>
          <div class="order-line order-total"><span class="strong">Total Due</span><span class="total-amount">$950.00</span></div>
        </div>
        <div class="order-features">
          <div class="feature-line"><span class="feature-ok">✅</span> Secure & encrypted</div>
          <div class="feature-line"><span class="feature-ok">✅</span> Money-back guarantee</div>
          <div class="feature-line"><span class="feature-ok">✅</span> PCI DSS compliant</div>
        </div>
        <div class="order-terms">By proceeding you agree to our <a href="#" class="link-pink">Terms of Service</a></div>
      </aside>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
