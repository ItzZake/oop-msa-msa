<?php
session_start();
$pageTitle = "Wellucation – Learn. Play. Grow";
$currentPage = "home";
$pageCss = 'index.css';
include 'header.php';
include 'navbar.php';
?>

<!-- Hero Section -->
<section class="hero-section">
  <div class="blob blob-pink hero-blob hero-blob--pink"></div>
  <div class="blob blob-blue hero-blob hero-blob--blue"></div>
  <div class="hero-blob hero-blob--yellow"></div>
  <div class="hero-float hero-float--star bounce">⭐</div>
  <div class="hero-float hero-float--rainbow bounce">🌈</div>
  <div class="hero-content container">
    <div class="hero-copy">
      <div class="hero-brand">
        <div class="logo-circle">🏫</div>
        <div>
          <div class="hero-brand-title">Wellucation</div>
          <div class="hero-brand-subtitle">Learn. Play. Grow</div>
        </div>
      </div>
      <h1 class="hero-heading">Where Little Stars<br><span class="hero-heading-highlight">Begin to Shine! ✨</span></h1>
      <p class="hero-copy-text">At Wellucation, we create a warm, safe, and joyful environment where children aged 2–5 discover the magic of learning through play, creativity, and friendship.</p>
      <div class="hero-actions">
        <a href="enroll.php" class="btn btn-primary">🌟 Enroll Today →</a>
        <a href="about.php" class="btn btn-outline">▶ Our Story</a>
      </div>
      <div class="hero-pill-list">
        <span class="hero-pill">🏆 Award-Winning</span>
        <span class="hero-pill">🔒 Safe & Secure</span>
        <span class="hero-pill">❤️ EYFS Accredited</span>
      </div>
    </div>
    <div class="hero-visual">
      <div class="visual-frame">
        <div class="visual-card visual-card--large">👧🏼📚</div>
        <div class="visual-card visual-card--small">👨‍👩‍👧</div>
        <div class="visual-badge">
          <span>🎓</span>
          <div><strong>248+</strong><span>Happy Families</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="stat-strip">
  <div class="container stat-strip-inner">
    <div class="stat-item"><div class="stat-icon stat-icon--pink"><span>👥</span></div><div><div class="stat-value stat-value--pink">248+</div><div class="stat-label">Happy Children</div></div></div>
    <div class="stat-item"><div class="stat-icon stat-icon--blue"><span>🎓</span></div><div><div class="stat-value stat-value--blue">18</div><div class="stat-label">Expert Teachers</div></div></div>
    <div class="stat-item"><div class="stat-icon stat-icon--yellow"><span>🏆</span></div><div><div class="stat-value stat-value--yellow">12</div><div class="stat-label">Award-Winning</div></div></div>
    <div class="stat-item"><div class="stat-icon stat-icon--green"><span>⏰</span></div><div><div class="stat-value stat-value--green">15+</div><div class="stat-label">Years of Care</div></div></div>
  </div>
</section>

<section class="section section--gray">
  <div class="container">
    <div class="section-header">
      <span class="badge badge-pink mb-4">🎯 Our Programs</span>
      <h2 class="section-header-title">Designed for Every Little Learner</h2>
      <p class="section-header-text">Age-appropriate programs that nurture curiosity, confidence, and creativity at every stage.</p>
    </div>
    <div class="program-grid">
      <div class="program-card program-card--pink"><div class="program-icon">🌱</div><span class="program-pill program-pill--pink">Ages 2 – 3</span><h3>Nursery Program</h3><p>A nurturing space where toddlers explore, play, and develop foundational social skills through guided activities.</p><a href="enroll.php" class="program-link program-link--pink">Learn more →</a></div>
      <div class="program-card program-card--blue"><div class="program-icon">🌼</div><span class="program-pill program-pill--blue">Ages 3 – 4</span><h3>Kindergarten 1</h3><p>Building early literacy, numeracy, and creativity through hands-on learning and imaginative play.</p><a href="enroll.php" class="program-link program-link--blue">Learn more →</a></div>
      <div class="program-card program-card--yellow"><div class="program-icon">🌟</div><span class="program-pill program-pill--yellow">Ages 4 – 5</span><h3>Kindergarten 2</h3><p>Preparing children for primary school with structured learning, problem-solving, and collaborative projects.</p><a href="enroll.php" class="program-link program-link--yellow">Learn more →</a></div>
      <div class="program-card program-card--green"><div class="program-icon">🎨</div><span class="program-pill program-pill--green">All Ages</span><h3>Creative Arts</h3><p>Unlocking imagination through painting, music, drama, and crafts that spark joy and self-expression.</p><a href="enroll.php" class="program-link program-link--green">Learn more →</a></div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
