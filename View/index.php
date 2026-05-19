<?php
session_start();
$pageTitle = "Wellucation – Learn. Play. Grow";
$currentPage = "home";
$pageCss = 'index.css';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wellucation – Learn. Play. Grow</title>
  <link rel="stylesheet" href="../view/css/Home.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
</head>
<body>
<?php
include 'header.php';
include 'navbar.php';
?>

  <!-- ══════════════ TOP BAR ══════════════ -->
  
  <!-- ══════════════ NAVBAR ══════════════ -->

  <!-- ══════════════ MAIN CONTENT ══════════════ -->
  <main class="main-content" id="mainContent">

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero__bg-shapes">
        <div class="hero__shape hero__shape--1"></div>
        <div class="hero__shape hero__shape--2"></div>
        <div class="hero__shape hero__shape--3"></div>
      </div>
      <div class="container hero__inner">
        <div class="hero__content">
          <div class="hero__badge">🎉 Now Enrolling for 2026–2027</div>
          <h1 class="hero__title">
            Where Little Minds
            <span class="hero__title-highlight">Blossom &amp; Grow</span>
          </h1>
          <p class="hero__desc">
            Wellucation is a nurturing early childhood learning community where curiosity is celebrated,
            creativity is sparked, and every child is guided to thrive — academically, socially, and emotionally.
          </p>
          <div class="hero__actions">
            <a href="../View/enroll.php" class="btn-enroll btn-hero-primary">🌟 Enroll Now</a>
            <a href="../View/about.php" class="btn-hero-secondary">Learn More →</a>
          </div>
          <div class="hero__stats">
            <div class="hero__stat">
              <span class="hero__stat-num">500+</span>
              <span class="hero__stat-label">Happy Kids</span>
            </div>
            <div class="hero__stat-divider"></div>
            <div class="hero__stat">
              <span class="hero__stat-num">15+</span>
              <span class="hero__stat-label">Years of Excellence</span>
            </div>
            <div class="hero__stat-divider"></div>
            <div class="hero__stat">
              <span class="hero__stat-num">98%</span>
              <span class="hero__stat-label">Parent Satisfaction</span>
            </div>
          </div>
        </div>
        <div class="hero__visual">
          <div class="hero__illustration">
            <div class="hero__emoji-float hero__emoji-float--1">🎨</div>
            <div class="hero__emoji-float hero__emoji-float--2">📚</div>
            <div class="hero__emoji-float hero__emoji-float--3">🎵</div>
            <div class="hero__emoji-float hero__emoji-float--4">⭐</div>
            <div class="hero__circle-main">
              <span style="font-size:6rem">🏫</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">Why Choose Us</span>
          <h2 class="section-title">A Place Where Kids Love to Learn</h2>
          <p class="section-desc">We combine play-based learning with structured development to give every child the best start in life.</p>
        </div>
        <div class="features__grid">
          <div class="feature-card">
            <div class="feature-card__icon">🎨</div>
            <h3 class="feature-card__title">Creative Arts</h3>
            <p class="feature-card__desc">Daily art, music, and drama activities that encourage self-expression and build confidence.</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">📚</div>
            <h3 class="feature-card__title">Early Literacy</h3>
            <p class="feature-card__desc">Structured phonics and reading programs designed for developing young readers.</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">🌿</div>
            <h3 class="feature-card__title">Outdoor Learning</h3>
            <p class="feature-card__desc">Safe, spacious play areas that encourage physical development and nature exploration.</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">🧠</div>
            <h3 class="feature-card__title">STEM Foundations</h3>
            <p class="feature-card__desc">Age-appropriate science and math activities that build logical thinking from day one.</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">🤝</div>
            <h3 class="feature-card__title">Social Skills</h3>
            <p class="feature-card__desc">Group activities and guided play to nurture empathy, teamwork, and communication.</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">🛡️</div>
            <h3 class="feature-card__title">Safe Environment</h3>
            <p class="feature-card__desc">CCTV-monitored, childproofed facilities with trained staff ensuring every child's safety.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Programs Section -->
    <section class="programs">
      <div class="container">
        <div class="section-header">
          <span class="section-badge">Our Programs</span>
          <h2 class="section-title">Find the Right Program for Your Child</h2>
        </div>
        <div class="programs__grid">
          <div class="program-card">
            <div class="program-card__age">Ages 2–3</div>
            <h3 class="program-card__name">Nursery</h3>
            <p class="program-card__desc">Gentle, play-focused introduction to structured learning and social interaction.</p>
            <a href="/enroll" class="program-card__btn">Enroll →</a>
          </div>
          <div class="program-card program-card--featured">
            <div class="program-card__badge">Most Popular</div>
            <div class="program-card__age">Ages 3–4</div>
            <h3 class="program-card__name">Kindergarten 1</h3>
            <p class="program-card__desc">Building foundational literacy, numeracy, and social skills in a warm classroom setting.</p>
            <a href="/enroll" class="program-card__btn program-card__btn--white">Enroll →</a>
          </div>
          <div class="program-card">
            <div class="program-card__age">Ages 4–5</div>
            <h3 class="program-card__name">Kindergarten 2</h3>
            <p class="program-card__desc">Advanced pre-school preparation with a focus on school readiness and independence.</p>
            <a href="/enroll" class="program-card__btn">Enroll →</a>
          </div>
          <div class="program-card">
            <div class="program-card__age">All Ages</div>
            <h3 class="program-card__name">After School Care</h3>
            <p class="program-card__desc">Safe, fun, and supervised after-school activities until 6:00 PM on weekdays.</p>
            <a href="/enroll" class="program-card__btn">Enroll →</a>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
      <div class="container cta-banner__inner">
        <div class="cta-banner__text">
          <h2 class="cta-banner__title">Ready to give your child the best start?</h2>
          <p class="cta-banner__desc">Spaces are limited — secure your child's spot today.</p>
        </div>
        <div class="cta-banner__actions">
          <a href="/enroll" class="cta-banner__btn-primary">🌟 Enroll Now</a>
          <a href="/contact" class="cta-banner__btn-secondary">Contact Us</a>
        </div>
      </div>
    </section>

  </main>

  <!-- ══════════════ FOOTER ══════════════ -->

  <script src="scripts/Home.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
