<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Secure Payment</title>
<<<<<<< HEAD
  <link rel="stylesheet" href="../css/Payment.css" />
=======
  <link rel="stylesheet" href="payment.css" />
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-blob hero-blob--left"></div>
    <div class="hero-blob hero-blob--right"></div>
    <div class="hero-content">
      <span class="hero-badge">💳 Payment</span>
      <h1 class="hero-title">Secure Payment Processing</h1>
      <p class="hero-subtitle">Complete your payment securely with our encrypted payment system</p>
    </div>
  </section>

  <!-- Payment Section -->
  <section class="payment-section">
    <div class="container">
      <div class="payment-grid">

        <!-- Payment Form -->
        <div class="form-card" id="formCard">
          <h2 class="form-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            Payment Method
          </h2>

          <!-- Method Selector -->
          <div class="method-grid">
            <label class="method-option" data-method="card">
              <input type="radio" name="paymentMethod" value="card" checked hidden />
              <span class="method-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
              </span>
              <span class="method-label">Credit Card</span>
            </label>

            <label class="method-option" data-method="debit">
              <input type="radio" name="paymentMethod" value="debit" hidden />
              <span class="method-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
              </span>
              <span class="method-label">Debit Card</span>
            </label>

            <label class="method-option" data-method="paypal">
              <input type="radio" name="paymentMethod" value="paypal" hidden />
              <span class="method-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              </span>
              <span class="method-label">PayPal</span>
            </label>
          </div>

          <!-- Card Form -->
          <form id="cardForm" class="card-form" novalidate>

            <!-- Parent & Child IDs (replace with session values once login exists) -->
            <div class="field-row">
              <div class="field-group">
                <label class="field-label" for="parentID">Parent ID</label>
                <div class="input-wrap">
                  <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="number" id="parentID" class="field-input" placeholder="e.g. 1" min="1" required />
                </div>
              </div>
              <div class="field-group">
                <label class="field-label" for="childID">Child ID</label>
                <div class="input-wrap">
                  <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="number" id="childID" class="field-input" placeholder="e.g. 1" min="1" required />
                </div>
              </div>
            </div>

            <div class="field-group">
              <label class="field-label" for="cardNumber">Card Number</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                <input type="text" id="cardNumber" class="field-input" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number" required />
              </div>
            </div>

            <div class="field-group">
              <label class="field-label" for="cardName">Cardholder Name</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" id="cardName" class="field-input" placeholder="John Doe" autocomplete="cc-name" required />
              </div>
            </div>

            <div class="field-row">
              <div class="field-group">
                <label class="field-label" for="expiry">Expiry Date</label>
                <div class="input-wrap">
                  <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                  <input type="text" id="expiry" class="field-input" placeholder="MM/YY" maxlength="5" autocomplete="cc-exp" required />
                </div>
              </div>

              <div class="field-group">
                <label class="field-label" for="cvv">CVV</label>
                <div class="input-wrap">
                  <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="11" height="11" x="11" y="11" rx="2" ry="2"/><path d="M7 7H4a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2v-3"/><path d="m14 4 3 3-7 7"/></svg>
                  <input type="text" id="cvv" class="field-input" placeholder="123" maxlength="3" autocomplete="cc-csc" required />
                </div>
              </div>
            </div>

            <button type="submit" class="pay-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="11" height="11" x="11" y="11" rx="2" ry="2"/><path d="M7 7H4a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2v-3"/><path d="m14 4 3 3-7 7"/></svg>
              Pay Securely
            </button>
          </form>

          <!-- PayPal Panel -->
          <div id="paypalPanel" class="paypal-panel hidden">
            <p class="paypal-text">You will be redirected to PayPal to complete your payment</p>
            <button class="paypal-btn" type="button">Continue with PayPal</button>
          </div>
        </div>

        <!-- Order Summary -->
        <aside class="summary-card" id="summaryCard">
          <h3 class="summary-title">Order Summary</h3>

          <ul class="summary-list">
            <li class="summary-row">
              <span>Monthly Tuition</span>
              <span class="summary-amount">$850.00</span>
            </li>
            <li class="summary-row">
              <span>Registration Fee</span>
              <span class="summary-amount">$150.00</span>
            </li>
            <li class="summary-row">
              <span>Activity Materials</span>
              <span class="summary-amount">$50.00</span>
            </li>
            <li class="summary-row summary-row--discount">
              <span>Early Bird Discount</span>
              <span>-$100.00</span>
            </li>
          </ul>

          <div class="summary-total">
            <span>Total Due</span>
            <span class="summary-total-amount">$950.00</span>
          </div>

          <ul class="trust-list">
            <li class="trust-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Secure &amp; encrypted
            </li>
            <li class="trust-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Money-back guarantee
            </li>
            <li class="trust-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="11" height="11" x="11" y="11" rx="2" ry="2"/><path d="M7 7H4a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2v-3"/><path d="m14 4 3 3-7 7"/></svg>
              PCI DSS compliant
            </li>
          </ul>

          <div class="terms-box">
            <p>By proceeding, you agree to our <a href="#" class="terms-link">Terms of Service</a></p>
          </div>
        </aside>

      </div>
    </div>
  </section>

  <!-- Toast Notification -->
  <div id="toast" class="toast hidden">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    Payment processed successfully! Thank you.
  </div>

<<<<<<< HEAD
  <script src="../scripts/Payment.js"></script>
=======
  <script src="payment.js"></script>
>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
</body>
</html>
