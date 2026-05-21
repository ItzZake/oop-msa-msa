<?php
session_start();
$pageTitle = "Contact Us – Wellucation";
$currentPage = "contact";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us – Wellucation</title>
  <link rel="stylesheet" href="../view/css/contact.css" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
?>

<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us – Wellucation</title>
  <link rel="stylesheet" href="../view/css/contact.css" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- ══════════════ HERO ══════════════ -->
  <section class="hero">
    <span class="hero__float hero__float--right">📬</span>
    <span class="hero__float hero__float--left">💌</span>
    <div class="hero__inner animate-fadein">
      <span class="hero__badge">📬 Get In Touch</span>
      <h1 class="hero__title">We'd Love to Hear from You!</h1>
      <p class="hero__sub">Have questions about enrollment, programs, or our facilities? Our friendly team is here to help every step of the way.</p>
    </div>
  </section>

=======
  <!-- ══════════════ HERO ══════════════ -->
  <section class="hero">
    <span class="hero__float hero__float--right">📬</span>
    <span class="hero__float hero__float--left">💌</span>
    <div class="hero__inner animate-fadein">
      <span class="hero__badge">📬 Get In Touch</span>
      <h1 class="hero__title">We'd Love to Hear from You!</h1>
      <p class="hero__sub">Have questions about enrollment, programs, or our facilities? Our friendly team is here to help every step of the way.</p>
    </div>
  </section>

>>>>>>> b1b6218c9ee9edc54c7912f2c06d23fc9e9a05bd
  <!-- ══════════════ CONTACT CARDS ══════════════ -->
  <section class="cards-section">
    <div class="container">
      <div class="cards-grid">

        <div class="contact-card animate-slide-up" style="--card-color: #E91E8C; --delay: 0s">
          <div class="contact-card__icon" style="background: #E91E8C22">
            <svg width="20" height="20" fill="none" stroke="#E91E8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.57 3.41 2 2 0 0 1 3.54 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.5a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div>
            <div class="contact-card__label">Phone Number</div>
            <div class="contact-card__value">+1 (555) 123-4567</div>
            <div class="contact-card__sub">Mon–Fri 8am–5pm</div>
          </div>
        </div>

        <div class="contact-card animate-slide-up" style="--card-color: #1565C0; --delay: 0.1s">
          <div class="contact-card__icon" style="background: #1565C022">
            <svg width="20" height="20" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          </div>
          <div>
            <div class="contact-card__label">Email Address</div>
            <div class="contact-card__value">hello@wellucation.edu</div>
            <div class="contact-card__sub">We reply within 24 hours</div>
          </div>
        </div>

        <div class="contact-card animate-slide-up" style="--card-color: #F59E0B; --delay: 0.2s">
          <div class="contact-card__icon" style="background: #F59E0B22">
            <svg width="20" height="20" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div>
            <div class="contact-card__label">Our Location</div>
            <div class="contact-card__value">123 Sunshine Lane</div>
            <div class="contact-card__sub">Kidstown, CA 90210</div>
          </div>
        </div>

        <div class="contact-card animate-slide-up" style="--card-color: #10B981; --delay: 0.3s">
          <div class="contact-card__icon" style="background: #10B98122">
            <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div>
            <div class="contact-card__label">School Hours</div>
            <div class="contact-card__value">Mon–Fri: 7:00 AM–6:00 PM</div>
            <div class="contact-card__sub">Sat: 8:00 AM–2:00 PM</div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ══════════════ FORM + MAP ══════════════ -->
  <section class="form-section">
    <div class="container">
      <div class="form-grid">

        <!-- Contact Form -->
        <div class="form-col animate-from-left">
          <h2 class="section-title">Send Us a Message</h2>
          <p class="section-sub">Fill in the form and our team will get back to you within 24 hours.</p>

          <div class="success-banner" id="successBanner" aria-live="polite">
            <svg width="20" height="20" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <div>
              <div class="success-banner__title">Message Sent Successfully! 🎉</div>
              <div class="success-banner__sub">We'll get back to you within 24 hours.</div>
            </div>
          </div>

          <form id="contactForm" class="contact-form" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" placeholder="Your full name" required />
              </div>
              <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="+1 (555) 000-0000" />
              </div>
              <div class="form-group">
                <label for="role">I am a...</label>
                <select id="role" name="role">
                  <option value="parent">Parent / Guardian</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="subject">Subject *</label>
              <input type="text" id="subject" name="subject" placeholder="e.g., Enrollment inquiry, School tour..." required />
            </div>
            <div class="form-group">
              <label for="message">Message *</label>
              <textarea id="message" name="message" rows="4" placeholder="Tell us how we can help you..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              Send Message
            </button>
          </form>
        </div>

        <!-- Map + Socials + WhatsApp -->
        <div class="map-col animate-from-right">

          <!-- Map Placeholder -->
          <div class="map-box">
            <div class="map-grid" aria-hidden="true"></div>
            <div class="map-content">
              <div class="map-pin">
                <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
              </div>
              <div class="map-text">
                <strong>Wellucation Nursery</strong>
                <span>123 Sunshine Lane, Kidstown, CA 90210</span>
              </div>
              <div class="map-emojis">🌳 🏫 🌳</div>
            </div>
            <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="map-link">Open in Maps →</a>
          </div>

          <!-- Social Media -->
          <div class="social-box">
            <h3 class="social-title">Follow Our Journey 🌟</h3>
            <div class="social-grid">
              <a href="#" class="social-item" style="background:#EBF4FF">
                <svg width="18" height="18" fill="#1877F2" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                <span style="color:#1877F2">@WellucationNursery</span>
              </a>
              <a href="#" class="social-item" style="background:#FFF0F4">
                <svg width="18" height="18" fill="none" stroke="#E4405F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                <span style="color:#E4405F">@wellucation</span>
              </a>
              <a href="#" class="social-item" style="background:#EFF9FF">
                <svg width="18" height="18" fill="none" stroke="#1DA1F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                <span style="color:#1DA1F2">@wellucation_edu</span>
              </a>
              <a href="#" class="social-item" style="background:#FFF5F5">
                <svg width="18" height="18" fill="#FF0000" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20.06 12 20.06 12 20.06s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon fill="white" points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                <span style="color:#FF0000">Wellucation TV</span>
              </a>
            </div>
          </div>

          <!-- WhatsApp CTA -->
          <div class="whatsapp-box">
            <div class="whatsapp-icon">
              <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="whatsapp-text">
              <strong>Chat on WhatsApp</strong>
              <span>Quick answers, any time!</span>
            </div>
            <a href="#" class="whatsapp-btn">Chat Now</a>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════ FAQ ══════════════ -->
  <section class="faq-section">
    <div class="container faq-inner">
      <div class="faq-header">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <p class="section-sub">Quick answers to the questions parents ask most often.</p>
      </div>
      <div class="faq-list">

        <div class="faq-item animate-slide-up" style="--border-color: #E91E8C; --delay: 0s">
          <div class="faq-q">❓ What age groups do you accept?</div>
          <div class="faq-a">We accept children from 2 to 5 years old in our Nursery, KG1, and KG2 programs.</div>
        </div>

        <div class="faq-item animate-slide-up" style="--border-color: #1565C0; --delay: 0.1s">
          <div class="faq-q">❓ How do I enroll my child?</div>
          <div class="faq-a">Contact us via phone or email to schedule a school tour. After the visit, complete our enrollment form and pay the registration fee.</div>
        </div>

        <div class="faq-item animate-slide-up" style="--border-color: #E91E8C; --delay: 0.2s">
          <div class="faq-q">❓ What is the teacher-to-child ratio?</div>
          <div class="faq-a">We maintain a 1:5 ratio in Nursery and 1:8 in KG classes to ensure personal attention.</div>
        </div>

        <div class="faq-item animate-slide-up" style="--border-color: #1565C0; --delay: 0.3s">
          <div class="faq-q">❓ Is there a waiting list?</div>
          <div class="faq-a">Spaces are limited. We recommend applying early. A deposit secures your child's place.</div>
        </div>

      </div>
    </div>
  </section>

  <script src="../view/scripts/Contact.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>