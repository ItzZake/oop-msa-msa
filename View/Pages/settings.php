<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account Settings</title>
  <link rel="stylesheet" href="../css/settings.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- ── Hero ─────────────────────────────────────────────────────── -->
  <section class="hero">
    <div class="hero-blob hero-blob--left"></div>
    <div class="hero-blob hero-blob--right"></div>
    <div class="hero-content animate-in">
      <span class="hero-badge">⚙️ Settings</span>
      <h1 class="hero-title">Account Settings</h1>
      <p class="hero-subtitle">Manage your profile, notifications, and preferences</p>
    </div>
  </section>

  <!-- ── Settings card ─────────────────────────────────────────────── -->
  <section class="settings-section">
    <div class="container">
      <div class="settings-card animate-in">

        <!-- Tab nav -->
        <div class="tab-nav" role="tablist">
          <button class="tab-btn active" role="tab" data-tab="profile" aria-selected="true">
            <!-- User icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profile
          </button>
          <button class="tab-btn" role="tab" data-tab="notifications" aria-selected="false">
            <!-- Bell icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            Notifications
          </button>
          <button class="tab-btn" role="tab" data-tab="security" aria-selected="false">
            <!-- Lock icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Security
          </button>
          <button class="tab-btn" role="tab" data-tab="preferences" aria-selected="false">
            <!-- Globe icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            Preferences
          </button>
        </div>

        <!-- ── Profile tab ─────────────────────────────────────────── -->
        <div class="tab-panel active" id="tab-profile" role="tabpanel">
          <div class="avatar-row">
            <div class="avatar">👩‍👧</div>
            <div>
              <h3 class="section-heading">Profile Picture</h3>
              <button class="btn-outline">Change Photo</button>
            </div>
          </div>

          <div class="field-grid">
            <div class="field-group">
              <label class="field-label" for="name">Full Name</label>
              <input class="field-input" type="text" id="name" value="Sarah Thompson" />
            </div>

            <div class="field-group">
              <label class="field-label" for="email">Email Address</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <input class="field-input has-icon" type="email" id="email" value="sarah.thompson@email.com" />
              </div>
            </div>

            <div class="field-group">
              <label class="field-label" for="phone">Phone Number</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5a2 2 0 0 1 1.99-2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 17.92z"/></svg>
                <input class="field-input has-icon" type="tel" id="phone" value="+1 (555) 123-4567" />
              </div>
            </div>

            <div class="field-group">
              <label class="field-label" for="address">Address</label>
              <input class="field-input" type="text" id="address" value="123 Main Street, Cityville, CA 90210" />
            </div>
          </div>

          <button class="btn-primary" id="saveProfile">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save Changes
          </button>
        </div>

        <!-- ── Notifications tab ───────────────────────────────────── -->
        <div class="tab-panel" id="tab-notifications" role="tabpanel">

          <h3 class="section-heading mb-4">Notification Channels</h3>
          <div class="toggle-list">
            <div class="toggle-row">
              <div>
                <div class="toggle-label">Email Notifications</div>
                <div class="toggle-desc">Receive updates via email</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="email" checked />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
            <div class="toggle-row">
              <div>
                <div class="toggle-label">SMS Notifications</div>
                <div class="toggle-desc">Get text message alerts</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="sms" checked />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
            <div class="toggle-row">
              <div>
                <div class="toggle-label">Push Notifications</div>
                <div class="toggle-desc">Browser and app notifications</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="push" checked />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
          </div>

          <h3 class="section-heading mb-4 mt-8">Notification Types</h3>
          <div class="toggle-list">
            <div class="toggle-row">
              <div>
                <div class="toggle-label">Attendance Updates</div>
                <div class="toggle-desc">Daily check-in/check-out notifications</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="attendance" checked />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
            <div class="toggle-row">
              <div>
                <div class="toggle-label">Assignment Reminders</div>
                <div class="toggle-desc">New assignments and deadlines</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="assignments" checked />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
            <div class="toggle-row">
              <div>
                <div class="toggle-label">New Messages</div>
                <div class="toggle-desc">Chat messages from teachers</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="messages" checked />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
            <div class="toggle-row">
              <div>
                <div class="toggle-label">Newsletter</div>
                <div class="toggle-desc">Monthly updates and news</div>
              </div>
              <label class="switch">
                <input type="checkbox" data-notif="newsletter" />
                <span class="switch-track"><span class="switch-thumb"></span></span>
              </label>
            </div>
          </div>

          <button class="btn-primary mt-8" id="saveNotifications">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save Preferences
          </button>
        </div>

        <!-- ── Security tab ───────────────────────────────────────── -->
        <div class="tab-panel" id="tab-security" role="tabpanel">

          <h3 class="section-heading mb-6">Change Password</h3>
          <div class="security-form">
            <div class="field-group">
              <label class="field-label" for="currentPassword">Current Password</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input class="field-input has-icon has-icon-right" type="password" id="currentPassword" placeholder="••••••••" />
                <button class="toggle-pw-btn" type="button" data-target="currentPassword" aria-label="Toggle password visibility">
                  <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  <svg class="eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" x2="23" y1="1" y2="23"/></svg>
                </button>
              </div>
            </div>

            <div class="field-group">
              <label class="field-label" for="newPassword">New Password</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input class="field-input has-icon" type="password" id="newPassword" placeholder="••••••••" />
              </div>
            </div>

            <div class="field-group">
              <label class="field-label" for="confirmPassword">Confirm New Password</label>
              <div class="input-wrap">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input class="field-input has-icon" type="password" id="confirmPassword" placeholder="••••••••" />
              </div>
            </div>

            <button class="btn-primary" id="updatePassword">Update Password</button>
          </div>

          <div class="twofa-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="twofa-icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <div>
              <h4 class="twofa-heading">Two-Factor Authentication</h4>
              <p class="twofa-desc">Add an extra layer of security to your account by enabling two-factor authentication</p>
              <button class="btn-outline">Enable 2FA</button>
            </div>
          </div>
        </div>

        <!-- ── Preferences tab ────────────────────────────────────── -->
        <div class="tab-panel" id="tab-preferences" role="tabpanel">
          <div class="prefs-form">

            <div class="field-group">
              <label class="field-label" for="language">Language</label>
              <select class="field-select" id="language">
                <option value="en" selected>English</option>
                <option value="es">Español</option>
                <option value="fr">Français</option>
                <option value="de">Deutsch</option>
              </select>
            </div>

            <div class="field-group">
              <label class="field-label" for="timezone">Timezone</label>
              <select class="field-select" id="timezone">
                <option value="PST" selected>Pacific Time (PST)</option>
                <option value="MST">Mountain Time (MST)</option>
                <option value="CST">Central Time (CST)</option>
                <option value="EST">Eastern Time (EST)</option>
              </select>
            </div>

            <div class="field-group">
              <label class="field-label" for="theme">Theme</label>
              <select class="field-select" id="theme">
                <option value="light" selected>Light</option>
                <option value="dark">Dark</option>
                <option value="auto">Auto (System)</option>
              </select>
            </div>

            <button class="btn-primary" id="savePreferences">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Save Preferences
            </button>
          </div>
        </div>

      </div><!-- /.settings-card -->
    </div>
  </section>

  <!-- Toast -->
  <div id="toast" class="toast hidden">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span id="toastMsg"></span>
  </div>

  <script src="../scripts/settings.js"></script>
</body>
</html>
