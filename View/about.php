<?php
session_start();
$pageTitle = "Application – Wellucation Nursery";
$currentPage = "application";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'header.php';
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us – Wellucation</title>
  <link rel="stylesheet" href="../view/css/Home.css" />
  <link rel="stylesheet" href="../view/css/About.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
</head>
<body>
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

  <script src="../view/scripts/About.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
