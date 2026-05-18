<?php
session_start();
$pageTitle = "Settings – Wellucation Nursery";
$currentPage = "settings";
$pageCss = 'settings.css';
include 'header.php';
include 'navbar.php';
?>

<section class="page-hero">
  <div class="page-hero__content">
    <h1 class="page-hero__title">Account Settings</h1>
    <p class="page-hero__subtitle">Manage your profile and preferences</p>
  </div>
</section>

<section class="section section--gray">
  <div class="container container-narrow">
    <div class="settings-panel">
      <div class="settings-group">
        <div class="settings-row">
          <div>
            <div class="settings-title">Email Notifications</div>
            <div class="settings-meta">Receive updates about your child</div>
          </div>
          <input type="checkbox" checked class="settings-toggle">
        </div>
      </div>
      <div class="settings-group">
        <div class="settings-row">
          <div>
            <div class="settings-title">SMS Alerts</div>
            <div class="settings-meta">Get text messages for urgent updates</div>
          </div>
          <input type="checkbox" class="settings-toggle">
        </div>
      </div>
      <div class="settings-group">
        <div class="settings-row">
          <div>
            <div class="settings-title">Newsletter</div>
            <div class="settings-meta">Monthly tips and school news</div>
          </div>
          <input type="checkbox" checked class="settings-toggle">
        </div>
      </div>
      <div class="settings-action-row">
        <button class="btn btn-primary">Save Preferences</button>
        <button class="btn">Cancel</button>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
