<?php
session_start();
$pageTitle = "About Us – Wellucation Nursery";
$currentPage = "about";
$pageCss = 'about.css';
include 'header.php';
include 'navbar.php';
?>

<section class="page-hero">
  <div class="page-hero__content">
    <h1 class="page-hero__title">About Wellucation</h1>
    <p class="page-hero__subtitle">Nurturing young minds for over 15 years</p>
  </div>
</section>

<section class="section">
  <div class="container about-content">
    <h2 class="about-feature-title">Our Mission</h2>
    <p class="about-feature-text">At Wellucation, we believe every child deserves the best start in life. Our mission is to provide a nurturing, safe, and stimulating environment where young learners can explore, grow, and thrive. We combine play-based learning with structured development to create well-rounded little learners.</p>
    <h2 class="about-feature-title about-feature-title--spaced">Why Choose Us?</h2>
    <div class="about-feature-grid">
      <div class="about-feature-card"><div class="about-feature-title">🏆 Award-Winning</div><p class="about-feature-text">12 international awards for excellence in early childhood education</p></div>
      <div class="about-feature-card"><div class="about-feature-title">👨‍🏫 Expert Staff</div><p class="about-feature-text">18 certified teachers with specialized training in child development</p></div>
      <div class="about-feature-card"><div class="about-feature-title">🎓 Quality Curriculum</div><p class="about-feature-text">Research-based programs that support cognitive, social, and emotional growth</p></div>
      <div class="about-feature-card"><div class="about-feature-title">🏥 Safe Environment</div><p class="about-feature-text">State-of-the-art facilities with strict safety and hygiene protocols</p></div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
