<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us – Wellucation</title>
  <link rel="stylesheet" href="../css/Home.css" />
  <link rel="stylesheet" href="../css/About.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- ══════════════ TOP BAR ══════════════ -->
  <div class="topbar">
    <div class="container topbar__inner">
      <div class="topbar__left">
        <span class="topbar__item">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"/></svg>
          +1 (555) 123-4567
        </span>
        <span class="topbar__item">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          hello@wellucation.edu
        </span>
      </div>
      <div class="topbar__right">
        <span>Follow us:</span>
        <a href="#" class="topbar__social" aria-label="Facebook">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="#" class="topbar__social" aria-label="Instagram">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
        </a>
        <a href="#" class="topbar__social" aria-label="Twitter">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
        </a>
        <a href="#" class="topbar__social" aria-label="YouTube">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><polygon points="10 15 15 12 10 9 10 15"/></svg>
        </a>
      </div>
    </div>
  </div>

  <!-- ══════════════ NAVBAR ══════════════ -->
  <nav class="navbar" id="navbar">
    <div class="container navbar__inner">

      <!-- Logo -->
      <a href="Home.html" class="navbar__logo">
        <div class="navbar__logo-img">
          <img src="logo.jpeg" alt="Wellucation" onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:1.5rem\'>🌟</span>';" />
        </div>
        <div class="navbar__logo-text">
          <span class="navbar__logo-name">Wellucation</span>
          <span class="navbar__logo-tagline">Learn. Play. Grow</span>
        </div>
      </a>

      <!-- Desktop Links: 4 main + More dropdown -->
      <div class="navbar__links" id="navLinks">
        <a href="Home.html" class="nav-link" data-path="/">Home</a>
        <a href="About.html" class="nav-link active" data-path="/about">About Us</a>
        <a href="Contact.html" class="nav-link" data-path="/contact">Contact Us</a>
        <a href="Login.html" class="nav-link" data-path="/login">Login</a>

        <!-- More dropdown -->
        <div class="nav-dropdown" id="moreDropdown">
          <button class="nav-link nav-dropdown__trigger" id="moreBtn" aria-expanded="false" aria-haspopup="true">
            More
            <svg class="nav-dropdown__chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="nav-dropdown__menu" id="moreMenu" role="menu">
            <a href="profiles.html"     class="nav-dropdown__item" role="menuitem">👤 Profiles</a>
            <a href="dashboard.html"    class="nav-dropdown__item" role="menuitem">📊 Dashboard</a>
            <a href="attendance.html"   class="nav-dropdown__item" role="menuitem">📅 Attendance</a>
            <a href="reports.html"      class="nav-dropdown__item" role="menuitem">📋 Reports</a>
            <a href="assignments.html"  class="nav-dropdown__item" role="menuitem">📝 Assignments</a>
            <div class="nav-dropdown__divider"></div>
            <a href="payment.html"      class="nav-dropdown__item" role="menuitem">💳 Payment</a>
            <a href="subscription.html" class="nav-dropdown__item" role="menuitem">⭐ Subscription</a>
            <a href="excuse.html"       class="nav-dropdown__item" role="menuitem">🙋 Excuse</a>
            <a href="messages.html"     class="nav-dropdown__item" role="menuitem">💬 Messages</a>
            <a href="application.html"  class="nav-dropdown__item" role="menuitem">📄 Application</a>
            <a href="settings.html"     class="nav-dropdown__item" role="menuitem">⚙️ Settings</a>
          </div>
        </div>
      </div>

      <!-- Right side -->
      <div class="navbar__right">
        <a href="enroll.html" class="btn-enroll btn-enroll--desktop">🌟 Enroll Now</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          <svg class="icon-close hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
      <a href="index.html"  class="nav-link" data-path="/">🏠 Home</a>
      <a href="about.html"  class="nav-link active" data-path="/about">ℹ️ About Us</a>
      <a href="contact.html" class="nav-link" data-path="/contact">📞 Contact Us</a>
      <a href="profiles.html" class="nav-link" data-path="/profiles">👤 Profiles</a>
      <a href="dashboard.html" class="nav-link" data-path="/dashboard">📊 Dashboard</a>
      <a href="attendance.html" class="nav-link" data-path="/attendance">📅 Attendance</a>
      <a href="reports.html" class="nav-link" data-path="/reports">📋 Reports</a>
      <a href="assignments.html" class="nav-link" data-path="/assignments">📝 Assignments</a>
      <a href="login.html"  class="nav-link" data-path="/login">🔐 Login</a>
      <a href="payment.html" class="nav-link" data-path="/payment">💳 Payment</a>
      <a href="subscription.html" class="nav-link" data-path="/subscription">⭐ Subscription</a>
      <a href="excuse.html" class="nav-link" data-path="/excuse">🙋 Excuse</a>
      <a href="messages.html" class="nav-link" data-path="/messages">💬 Messages</a>
      <a href="application.html" class="nav-link" data-path="/application">📄 Application</a>
      <a href="settings.html" class="nav-link" data-path="/settings">⚙️ Settings</a>
      <a href="enroll.html" class="btn-enroll btn-enroll--mobile">🌟 Enroll Now</a>
    </div>
  </nav>

  <!-- ══════════════ MAIN CONTENT ══════════════ -->
  <main class="main-content">

    <!-- ── HERO ── -->
    <section class="about-hero">
      <div class="about-hero__deco about-hero__deco--1">⭐</div>
      <div class="about-hero__deco about-hero__deco--2">🌈</div>
      <div class="container about-hero__inner reveal">
        <span class="section-badge section-badge--white">💙 About Wellucation</span>
        <h1 class="about-hero__title">Our Story, Our Heart</h1>
        <p class="about-hero__desc">
          For over 15 years, we've been shaping young minds and building a community where every child feels loved, safe, and empowered to grow.
        </p>
      </div>
    </section>

    <!-- ── MISSION & VISION ── -->
    <section class="about-mv">
      <div class="container about-mv__grid">

        <!-- Mission -->
        <div class="mv-card mv-card--pink reveal">
          <div class="mv-card__icon" style="background:#E91E8C;">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          </div>
          <h2 class="mv-card__title" style="color:#E91E8C;">Our Mission</h2>
          <p class="mv-card__desc">
            To provide a nurturing, inclusive, and stimulating environment where every child between 2–5 years can explore their full potential through play-based learning, creativity, and positive relationships — while feeling deeply valued and safe.
          </p>
          <ul class="mv-card__list">
            <li><span class="mv-card__check" style="color:#E91E8C;">✓</span> Every child is unique and celebrated</li>
            <li><span class="mv-card__check" style="color:#E91E8C;">✓</span> Play is the foundation of learning</li>
            <li><span class="mv-card__check" style="color:#E91E8C;">✓</span> Families are our partners in growth</li>
          </ul>
        </div>

        <!-- Vision -->
        <div class="mv-card mv-card--blue reveal">
          <div class="mv-card__icon" style="background:#1565C0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </div>
          <h2 class="mv-card__title" style="color:#1565C0;">Our Vision</h2>
          <p class="mv-card__desc">
            To be the region's most trusted center of early childhood excellence — where every graduate leaves with a lifelong love of learning, strong social skills, and the confidence to embrace the world with curiosity and kindness.
          </p>
          <ul class="mv-card__list">
            <li><span class="mv-card__check" style="color:#1565C0;">✓</span> Globally recognized quality education</li>
            <li><span class="mv-card__check" style="color:#1565C0;">✓</span> Technology-enhanced learning</li>
            <li><span class="mv-card__check" style="color:#1565C0;">✓</span> Inclusive for every child, every ability</li>
          </ul>
        </div>

      </div>
    </section>

    <!-- ── TIMELINE ── -->
    <section class="about-timeline">
      <div class="container">
        <div class="section-header reveal">
          <span class="section-badge">📖 Our Journey</span>
          <h2 class="section-title" style="color:#E91E8C;">15 Years of Growing Together</h2>
          <p class="section-desc">From a small classroom dream to a thriving community — our story is written by every child who walked through our doors.</p>
        </div>

        <div class="timeline">
          <div class="timeline__line"></div>

          <!-- 2010 -->
          <div class="timeline__row timeline__row--left reveal">
            <div class="timeline__card">
              <div class="timeline__card-year" style="color:#E91E8C;">Founded</div>
              <p class="timeline__card-desc">Wellucation opened its doors with just 15 children and 3 dedicated teachers.</p>
            </div>
            <div class="timeline__dot timeline__dot--pink">2010</div>
            <div class="timeline__spacer"></div>
          </div>

          <!-- 2013 -->
          <div class="timeline__row timeline__row--right reveal">
            <div class="timeline__spacer"></div>
            <div class="timeline__dot timeline__dot--blue">2013</div>
            <div class="timeline__card">
              <div class="timeline__card-year" style="color:#1565C0;">Expansion</div>
              <p class="timeline__card-desc">Grew to 80 children, added KG1 &amp; KG2 programs, and built our outdoor playground.</p>
            </div>
          </div>

          <!-- 2016 -->
          <div class="timeline__row timeline__row--left reveal">
            <div class="timeline__card">
              <div class="timeline__card-year" style="color:#E91E8C;">Accreditation</div>
              <p class="timeline__card-desc">Received prestigious EYFS Gold Accreditation and national recognition award.</p>
            </div>
            <div class="timeline__dot timeline__dot--pink">2016</div>
            <div class="timeline__spacer"></div>
          </div>

          <!-- 2019 -->
          <div class="timeline__row timeline__row--right reveal">
            <div class="timeline__spacer"></div>
            <div class="timeline__dot timeline__dot--blue">2019</div>
            <div class="timeline__card">
              <div class="timeline__card-year" style="color:#1565C0;">New Campus</div>
              <p class="timeline__card-desc">Moved to our current larger campus with dedicated art, music, and STEM rooms.</p>
            </div>
          </div>

          <!-- 2023 -->
          <div class="timeline__row timeline__row--left reveal">
            <div class="timeline__card">
              <div class="timeline__card-year" style="color:#E91E8C;">Digital Leap</div>
              <p class="timeline__card-desc">Launched our parent app and digital attendance system for seamless communication.</p>
            </div>
            <div class="timeline__dot timeline__dot--pink">2023</div>
            <div class="timeline__spacer"></div>
          </div>

          <!-- 2026 -->
          <div class="timeline__row timeline__row--right reveal">
            <div class="timeline__spacer"></div>
            <div class="timeline__dot timeline__dot--blue">2026</div>
            <div class="timeline__card">
              <div class="timeline__card-year" style="color:#1565C0;">Today</div>
              <p class="timeline__card-desc">Proudly serving 248+ children, 18 staff, and continuing to grow with love.</p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── VALUES ── -->
    <section class="about-values">
      <div class="container">
        <div class="section-header reveal">
          <span class="section-badge section-badge--blue">💎 Our Values</span>
          <h2 class="section-title" style="color:#E91E8C;">What We Believe In</h2>
        </div>
        <div class="values__grid">

          <div class="value-card value-card--pink reveal">
            <div class="value-card__icon" style="background:#E91E8C;">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </div>
            <h3 class="value-card__title">Compassion</h3>
            <p class="value-card__desc">Every child is treated with warmth, respect, and unconditional care.</p>
          </div>

          <div class="value-card value-card--blue reveal">
            <div class="value-card__icon" style="background:#1565C0;">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <h3 class="value-card__title">Excellence</h3>
            <p class="value-card__desc">We hold high standards in curriculum, teaching, and child development.</p>
          </div>

          <div class="value-card value-card--pink reveal">
            <div class="value-card__icon" style="background:#E91E8C;">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h3 class="value-card__title">Community</h3>
            <p class="value-card__desc">We build a strong partnership between families, teachers, and children.</p>
          </div>

          <div class="value-card value-card--blue reveal">
            <div class="value-card__icon" style="background:#1565C0;">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
            </div>
            <h3 class="value-card__title">Innovation</h3>
            <p class="value-card__desc">We continuously evolve our methods with the latest in early childhood research.</p>
          </div>

        </div>
      </div>
    </section>

    <!-- ── CAMPUS ── -->
    <section class="about-campus">
      <div class="container about-campus__grid">

        <!-- Text side -->
        <div class="campus-text reveal">
          <span class="section-badge">🏫 Our Campus</span>
          <h2 class="campus-text__title">A Place Designed for Children</h2>
          <p class="campus-text__desc">
            Our 5,000 sq ft campus is thoughtfully designed with children's safety, creativity, and comfort as top priorities. From bright, airy classrooms to our magical outdoor garden, every corner invites discovery.
          </p>
          <div class="campus-features">
            <div class="campus-feature">
              <span class="campus-feature__icon">🎨</span>
              <span class="campus-feature__label">Art Studio</span>
            </div>
            <div class="campus-feature">
              <span class="campus-feature__icon">🎵</span>
              <span class="campus-feature__label">Music Room</span>
            </div>
            <div class="campus-feature">
              <span class="campus-feature__icon">🌿</span>
              <span class="campus-feature__label">Garden Area</span>
            </div>
            <div class="campus-feature">
              <span class="campus-feature__icon">📚</span>
              <span class="campus-feature__label">Library Corner</span>
            </div>
            <div class="campus-feature">
              <span class="campus-feature__icon">🧩</span>
              <span class="campus-feature__label">Sensory Play Zone</span>
            </div>
            <div class="campus-feature">
              <span class="campus-feature__icon">🏃</span>
              <span class="campus-feature__label">Indoor Gym</span>
            </div>
          </div>
        </div>

        <!-- Image side -->
        <div class="campus-images reveal">
          <img
            src="https://images.unsplash.com/photo-1701215373698-9df8210d6201?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080"
            alt="Wellucation Campus"
            class="campus-img campus-img--wide"
            loading="lazy"
          />
          <div class="campus-img-row">
            <img
              src="https://images.unsplash.com/photo-1605627079912-97c3810a11a4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=600"
              alt="Activities"
              class="campus-img campus-img--half"
              loading="lazy"
            />
            <img
              src="https://images.unsplash.com/photo-1475563011407-6bf489b1c361?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=600"
              alt="Teaching"
              class="campus-img campus-img--half"
              loading="lazy"
            />
          </div>
        </div>

      </div>
    </section>

    <!-- ── STAFF ── -->
    <section class="about-staff">
      <div class="container">
        <div class="section-header reveal">
          <span class="section-badge">👩‍🏫 Meet the Team</span>
          <h2 class="section-title" style="color:#E91E8C;">The Hearts Behind Wellucation</h2>
          <p class="section-desc">Our dedicated educators bring passion, qualifications, and genuine love for children to every single day.</p>
        </div>
        <div class="staff__grid">

          <!-- Ms. Sarah Collins -->
          <div class="staff-card reveal">
            <div class="staff-card__img-wrap">
              <img src="https://images.unsplash.com/photo-1573496800808-56566a492b63?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=600" alt="Ms. Sarah Collins" class="staff-card__img" loading="lazy" />
              <div class="staff-card__emoji">👩‍💼</div>
            </div>
            <div class="staff-card__body">
              <h3 class="staff-card__name">Ms. Sarah Collins</h3>
              <div class="staff-card__role" style="color:#E91E8C;">Principal &amp; Founder</div>
              <div class="staff-card__meta">
                <span>📚 School Administration</span>
                <span>⏳ 18 years experience</span>
                <span>🎓 M.Ed Early Childhood</span>
              </div>
              <div class="staff-card__stars">★★★★★</div>
            </div>
          </div>

          <!-- Ms. Emily Watson -->
          <div class="staff-card reveal">
            <div class="staff-card__img-wrap">
              <img src="https://images.unsplash.com/photo-1746513399803-e988cc54e812?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=600" alt="Ms. Emily Watson" class="staff-card__img" loading="lazy" />
              <div class="staff-card__emoji">👩‍🏫</div>
            </div>
            <div class="staff-card__body">
              <h3 class="staff-card__name">Ms. Emily Watson</h3>
              <div class="staff-card__role" style="color:#1565C0;">Head of Nursery</div>
              <div class="staff-card__meta">
                <span>📚 Nursery (Ages 2–3)</span>
                <span>⏳ 12 years experience</span>
                <span>🎓 B.Ed. Child Psychology</span>
              </div>
              <div class="staff-card__stars">★★★★★</div>
            </div>
          </div>

          <!-- Ms. Aisha Malik -->
          <div class="staff-card reveal">
            <div class="staff-card__img-wrap">
              <img src="https://images.unsplash.com/photo-1573496800808-56566a492b63?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=600" alt="Ms. Aisha Malik" class="staff-card__img" loading="lazy" />
              <div class="staff-card__emoji">🎨</div>
            </div>
            <div class="staff-card__body">
              <h3 class="staff-card__name">Ms. Aisha Malik</h3>
              <div class="staff-card__role" style="color:#F59E0B;">KG1 Lead Teacher</div>
              <div class="staff-card__meta">
                <span>📚 Kindergarten 1</span>
                <span>⏳ 9 years experience</span>
                <span>🎓 BTEC Level 5 ECD</span>
              </div>
              <div class="staff-card__stars">★★★★★</div>
            </div>
          </div>

          <!-- Mr. James Rivera -->
          <div class="staff-card reveal">
            <div class="staff-card__img-wrap">
              <img src="https://images.unsplash.com/photo-1475563011407-6bf489b1c361?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=600" alt="Mr. James Rivera" class="staff-card__img" loading="lazy" />
              <div class="staff-card__emoji">🎵</div>
            </div>
            <div class="staff-card__body">
              <h3 class="staff-card__name">Mr. James Rivera</h3>
              <div class="staff-card__role" style="color:#10B981;">Arts &amp; Music Teacher</div>
              <div class="staff-card__meta">
                <span>📚 All Classes</span>
                <span>⏳ 7 years experience</span>
                <span>🎓 BA Music Education</span>
              </div>
              <div class="staff-card__stars">★★★★★</div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── CTA ── -->
    <section class="about-cta">
      <div class="container about-cta__inner reveal">
        <h2 class="about-cta__title">Come See Us in Person 🏫</h2>
        <p class="about-cta__desc">Book a free school tour and see why families love Wellucation. We'd love to show you around!</p>
        <a href="contact.html" class="about-cta__btn">
          📅 Book a School Tour
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      </div>
    </section>

  </main>

  <!-- ══════════════ FOOTER ══════════════ -->
  <footer class="footer">
    <div class="container footer__grid">

      <div class="footer__col">
        <div class="footer__brand">
          <div class="footer__logo-img">
            <img src="logo.jpeg" alt="Wellucation" onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:1.4rem\'>🌟</span>';" />
          </div>
          <div>
            <div class="footer__brand-name">Wellucation</div>
            <div class="footer__brand-tagline">Learn. Play. Grow</div>
          </div>
        </div>
        <p class="footer__desc">Nurturing young minds with love, creativity, and excellence in early childhood education.</p>
        <div class="footer__socials">
          <a href="#" class="footer__social" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
          <a href="#" class="footer__social" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg></a>
          <a href="#" class="footer__social" aria-label="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg></a>
          <a href="#" class="footer__social" aria-label="YouTube"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><polygon points="10 15 15 12 10 9 10 15"/></svg></a>
        </div>
      </div>

      <div class="footer__col">
        <h4 class="footer__heading">Quick Links</h4>
        <ul class="footer__list">
          <li><a href="Home.html"   class="footer__link"><span class="footer__arrow">›</span> Home</a></li>
          <li><a href="About.html"   class="footer__link"><span class="footer__arrow">›</span> About Us</a></li>
          <li><a href="Contact.html" class="footer__link"><span class="footer__arrow">›</span> Contact Us</a></li>
          <li><a href="Dashboard.html" class="footer__link"><span class="footer__arrow">›</span> Dashboard</a></li>
          <li><a href="Attendance.html" class="footer__link"><span class="footer__arrow">›</span> Attendance</a></li>
          <li><a href="Reports.html" class="footer__link"><span class="footer__arrow">›</span> Reports</a></li>
          <li><a href="Enroll.html"  class="footer__link"><span class="footer__arrow">›</span> Enroll Now</a></li>
          <li><a href="Login.html"   class="footer__link"><span class="footer__arrow">›</span> Login</a></li>
          <li><a href="Messages.html" class="footer__link"><span class="footer__arrow">›</span> Messages</a></li>
          <li><a href="Settings.html" class="footer__link"><span class="footer__arrow">›</span> Settings</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4 class="footer__heading">Our Programs</h4>
        <ul class="footer__list">
          <li class="footer__item"><span class="footer__arrow">›</span> Nursery (Ages 2–3)</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Kindergarten 1 (Ages 3–4)</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Kindergarten 2 (Ages 4–5)</li>
          <li class="footer__item"><span class="footer__arrow">›</span> After School Care</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Summer Camp</li>
          <li class="footer__item"><span class="footer__arrow">›</span> Special Needs Support</li>
        </ul>
      </div>

      <div class="footer__col">
        <h4 class="footer__heading">Get In Touch</h4>
        <div class="footer__contact">
          <div class="footer__contact-row">
            <svg class="footer__contact-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>123 Sunshine Lane, Kidstown, CA 90210</span>
          </div>
          <div class="footer__contact-row">
            <svg class="footer__contact-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.17 3.38 2 2 0 0 1 3.13 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16h1z"/></svg>
            <span>+1 (555) 123-4567</span>
          </div>
          <div class="footer__contact-row">
            <svg class="footer__contact-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>hello@wellucation.edu</span>
          </div>
        </div>
        <div class="footer__hours">
          <p class="footer__hours-label">School Hours</p>
          <p class="footer__hours-main">Mon – Fri: 7:00 AM – 6:00 PM</p>
          <p class="footer__hours-sub">Sat: 8:00 AM – 2:00 PM</p>
        </div>
      </div>

    </div>
    <div class="footer__bottom">
      <div class="container footer__bottom-inner">
        <p class="footer__copy">© 2026 Wellucation Nursery. All rights reserved.</p>
        <p class="footer__made-with">
          Made with
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#E91E8C" stroke="#E91E8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
          for little learners
        </p>
      </div>
    </div>
  </footer>

  <script src="../scripts/About.js"></script>
</body>
</html>