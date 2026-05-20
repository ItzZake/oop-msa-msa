<?php
// ── Session & Authentication ──
session_start();
include "Header.php";
include "navbar.php";

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Controller/Login.php');
    exit;
}

// Only keep session data for security
$userRole = $_SESSION['user_role'] ?? 'User';
$userId = $_SESSION['user_id'];

// Initialize userData object - will be populated via AJAX from controller
$jsUserData = json_encode([
    'userID' => $userId,
    'firstName' => '',
    'lastName' => '',
    'displayName' => '',
    'email' => '',
    'phoneNumber' => '',
    'role' => $userRole,
    'status' => 'Active',
    'experience' => 0,
    'qualifications' => '',
    'specialization' => '',
    'childrenCount' => 0,
]);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile – Wellucation</title>
    <link rel="stylesheet" href="css/EditProfile.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&family=DM+Serif+Display&display=swap"
      rel="stylesheet"
    />
    <script>
      (function () {
        try {
          var path = location.pathname;
          var baseHref = path.substring(0, path.lastIndexOf("/") + 1) || "/";
          var b = document.createElement("base");
          b.href = baseHref;
          document.head.appendChild(b);
        } catch (e) {
          /* noop */
        }
      })();
    </script>
    <script>
      // Pass PHP data to JavaScript
      const userData = <?php echo $jsUserData; ?>;
    </script>
  </head>
  <body>
    <!-- ══ HERO ══ -->
    <section class="ep-hero">
      <div class="ep-hero__blob ep-hero__blob--left"></div>
      <div class="ep-hero__blob ep-hero__blob--right"></div>
      <div class="ep-hero__inner">
        <a href="Profile.php" class="ep-back">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <polyline points="15 18 9 12 15 6" />
          </svg>
          Back to Profiles
        </a>
        <span class="ep-hero__badge">✏️ Edit Profile</span>
        <h1 class="ep-hero__title">Manage Your Profile</h1>
        <p class="ep-hero__sub">
          Update personal info, manage linked users, and keep your account
          current.
        </p>
      </div>
    </section>

    <!-- ══ MAIN ══ -->
    <main class="ep-main container">
      <!-- LEFT: profile card + avatar editor -->
      <aside class="ep-sidebar">
        <div class="ep-avatar-card">
          <div class="ep-avatar-banner"></div>
          <div class="ep-avatar-body">
            <div class="ep-avatar-ring" id="avatarRing">
              <div class="ep-avatar-fallback">👩‍🏫</div>
              <label
                class="ep-avatar-overlay"
                for="avatarUpload"
                title="Change photo"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path
                    d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"
                  />
                  <circle cx="12" cy="13" r="4" />
                </svg>
                Change
              </label>
              <input
                type="file"
                id="avatarUpload"
                accept="image/*"
                class="hidden"
              />
            </div>
            <h2 class="ep-sidebar__name" id="sidebarName">Loading...</h2>
            <div class="ep-sidebar__role" id="sidebarRole">User</div>
            <div class="ep-sidebar__meta">
              <div class="ep-sidebar__meta-item" id="sidebarEmail">✉️ Loading...</div>
              <div class="ep-sidebar__meta-item" id="sidebarExtra"></div>
            </div>
          </div>
        </div>

        <!-- Danger zone -->
        <div class="ep-danger-card">
          <h4 class="ep-danger__title">⚠️ Danger Zone</h4>
          <p class="ep-danger__desc">
            Permanently delete this account and all associated data. This action
            cannot be undone.
          </p>
          <button class="ep-danger__btn" id="deleteAccountBtn">
            🗑️ Delete Account
          </button>
        </div>
      </aside>

      <!-- RIGHT: form panels -->
      <div class="ep-panels">
        <!-- Tab bar -->
        <div class="ep-tab-bar">
          <button class="ep-tab active" data-tab="personal">
            👤 Personal Info
          </button>
          <button class="ep-tab" data-tab="security">🔒 Security</button>
          
          <button class="ep-tab" data-tab="notifications">
            🔔 Notifications
          </button>
        </div>

        <!-- ── PERSONAL INFO ── -->
        <div class="ep-panel active" id="panel-personal">
          <div class="ep-panel__header">
            <h3>Personal Information</h3>
            <p>Update your display name, contact details, and role info.</p>
          </div>
          <div class="ep-form">
            <div class="ep-field-row">
              <div class="ep-field">
                <label>First Name</label>
                <input
                  type="text"
                  id="firstName"
                  value=""
                  placeholder="First name"
                />
              </div>
              <div class="ep-field">
                <label>Last Name</label>
                <input
                  type="text"
                  id="lastName"
                  value=""
                  placeholder="Last name"
                />
              </div>
            </div>
            <div class="ep-field">
              <label>Display Name</label>
              <input
                type="text"
                id="displayName"
                value=""
                placeholder="Display name"
              />
            </div>
            <div class="ep-field">
              <label>Email Address</label>
              <input
                type="email"
                id="email"
                value=""
                placeholder="Email"
              />
            </div>
            <div class="ep-field">
              <label>Phone Number</label>
              <input
                type="tel"
                id="phone"
                value=""
                placeholder="Phone number"
              />
            </div>
            <div class="ep-field-row">
              <div class="ep-field">
                <label>Role</label>
                <select id="role">
                  <option value="teacher">Teacher</option>
                  <option value="admin">Admin</option>
                  <option value="parent">Parent</option>
                </select>
              </div>
              <div class="ep-field">
                <label>Class / Department</label>
                <input
                  type="text"
                  id="classField"
                  value=""
                  placeholder="Class"
                />
              </div>
            </div>
            <div class="ep-field">
              <label>Years of Experience</label>
              <input
                type="number"
                id="experience"
                value=""
                placeholder="Years"
                min="0"
              />
            </div>
            <div class="ep-field">
              <label>Bio</label>
              <textarea
                id="bio"
                rows="3"
                placeholder="Tell us about yourself..."
              ></textarea
              >
            </div>
            <div class="ep-form-actions">
              <button
                class="ep-btn ep-btn--ghost"
                type="button"
                onclick="resetPersonal()"
              >
                Discard Changes
              </button>
              <button
                class="ep-btn ep-btn--primary"
                type="button"
                onclick="savePersonal()"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="20 6 9 17 4 12" />
                </svg>
                Save Changes
              </button>
            </div>
          </div>
        </div>

        <!-- ── SECURITY ── -->
        <div class="ep-panel" id="panel-security">
          <div class="ep-panel__header">
            <h3>Security Settings</h3>
            <p>Update your password and manage account security preferences.</p>
          </div>
          <div class="ep-form">
            <div class="ep-field">
              <label>Current Password</label>
              <div class="ep-pwd-wrap">
                <input
                  type="password"
                  id="currentPwd"
                  placeholder="Enter current password"
                />
                <button
                  class="ep-pwd-toggle"
                  type="button"
                  data-target="currentPwd"
                >
                  👁️
                </button>
              </div>
            </div>
            <div class="ep-field">
              <label>New Password</label>
              <div class="ep-pwd-wrap">
                <input
                  type="password"
                  id="newPwd"
                  placeholder="Min 8 characters"
                />
                <button
                  class="ep-pwd-toggle"
                  type="button"
                  data-target="newPwd"
                >
                  👁️
                </button>
              </div>
              <div class="ep-pwd-strength" id="pwdStrength">
                <div class="ep-pwd-strength-bar">
                  <div class="ep-pwd-strength-fill" id="pwdFill"></div>
                </div>
                <span class="ep-pwd-strength-label" id="pwdLabel"
                  >Enter a password</span
                >
              </div>
            </div>
            <div class="ep-field">
              <label>Confirm New Password</label>
              <div class="ep-pwd-wrap">
                <input
                  type="password"
                  id="confirmPwd"
                  placeholder="Repeat new password"
                />
                <button
                  class="ep-pwd-toggle"
                  type="button"
                  data-target="confirmPwd"
                >
                  👁️
                </button>
              </div>
            </div>
            <div class="ep-divider"></div>
            <div class="ep-field">
              <label>Two-Factor Authentication</label>
              <div class="ep-toggle-row">
                <div>
                  <div class="ep-toggle-title">Enable 2FA</div>
                  <div class="ep-toggle-desc">
                    Extra security layer via email or authenticator app
                  </div>
                </div>
                <label class="ep-switch">
                  <input type="checkbox" id="twoFaToggle" />
                  <span class="ep-switch-track"
                    ><span class="ep-switch-thumb"></span
                  ></span>
                </label>
              </div>
            </div>
            <div class="ep-field">
              <label>Login Notifications</label>
              <div class="ep-toggle-row">
                <div>
                  <div class="ep-toggle-title">Notify on new login</div>
                  <div class="ep-toggle-desc">
                    Get an email whenever a new device logs in
                  </div>
                </div>
                <label class="ep-switch">
                  <input type="checkbox" checked id="loginNotifToggle" />
                  <span class="ep-switch-track"
                    ><span class="ep-switch-thumb"></span
                  ></span>
                </label>
              </div>
            </div>
            <div class="ep-form-actions">
              <button class="ep-btn ep-btn--ghost" type="button">Cancel</button>
              <button
                class="ep-btn ep-btn--primary"
                type="button"
                onclick="changePassword()"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <rect width="11" height="11" x="3" y="11" rx="2" ry="2" />
                  <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
                Update Password
              </button>
            </div>
          </div>
        </div>

        <!-- ── MANAGE USERS ── -->
        <div class="ep-panel" id="panel-users">
          <div class="ep-panel__header">
            <h3>Manage Users</h3>
            <p>Add new users or remove existing ones linked to this profile.</p>
          </div>

          <!-- Search + Add -->
          <div class="ep-users-toolbar">
            <div class="ep-search-wrap">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
              </svg>
              <input
                type="text"
                id="userSearch"
                placeholder="Search users…"
                oninput="filterUsers(this.value)"
              />
            </div>
            <button class="ep-btn ep-btn--primary ep-btn--sm" id="openAddModal">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M5 12h14" />
                <path d="M12 5v14" />
              </svg>
              Add User
            </button>
          </div>

          <!-- Filter pills -->
          <div class="ep-filter-pills">
            <button class="ep-pill active" data-filter="all">All</button>
            <button class="ep-pill" data-filter="teacher">Teachers</button>
            <button class="ep-pill" data-filter="admin">Admins</button>
            <button class="ep-pill" data-filter="parent">Parents</button>
            <button class="ep-pill" data-filter="child">Children</button>
          </div>

          <!-- User list -->
          <div class="ep-user-list" id="userList"></div>
        </div>

        <!-- ── NOTIFICATIONS ── -->
        <div class="ep-panel" id="panel-notifications">
          <div class="ep-panel__header">
            <h3>Notification Preferences</h3>
            <p>Choose what you want to be notified about and how.</p>
          </div>
          <div class="ep-form">
            <div class="ep-notif-section">
              <div class="ep-notif-section-label">📧 Email Notifications</div>
              <div class="ep-field">
                <div class="ep-toggle-row">
                  <div>
                    <div class="ep-toggle-title">Attendance Alerts</div>
                    <div class="ep-toggle-desc">
                      Notify when a child is marked absent or late
                    </div>
                  </div>
                  <label class="ep-switch"
                    ><input type="checkbox" checked /><span
                      class="ep-switch-track"
                      ><span class="ep-switch-thumb"></span></span
                  ></label>
                </div>
              </div>
              <div class="ep-field">
                <div class="ep-toggle-row">
                  <div>
                    <div class="ep-toggle-title">New Messages</div>
                    <div class="ep-toggle-desc">
                      Email when you receive a new message
                    </div>
                  </div>
                  <label class="ep-switch"
                    ><input type="checkbox" checked /><span
                      class="ep-switch-track"
                      ><span class="ep-switch-thumb"></span></span
                  ></label>
                </div>
              </div>
              <div class="ep-field">
                <div class="ep-toggle-row">
                  <div>
                    <div class="ep-toggle-title">Report Ready</div>
                    <div class="ep-toggle-desc">
                      Notify when a new report is available
                    </div>
                  </div>
                  <label class="ep-switch"
                    ><input type="checkbox" /><span class="ep-switch-track"
                      ><span class="ep-switch-thumb"></span></span
                  ></label>
                </div>
              </div>
            </div>
            <div class="ep-divider"></div>
            <div class="ep-notif-section">
              <div class="ep-notif-section-label">📱 Push Notifications</div>
              <div class="ep-field">
                <div class="ep-toggle-row">
                  <div>
                    <div class="ep-toggle-title">Event Reminders</div>
                    <div class="ep-toggle-desc">
                      Reminders for school events and meetings
                    </div>
                  </div>
                  <label class="ep-switch"
                    ><input type="checkbox" checked /><span
                      class="ep-switch-track"
                      ><span class="ep-switch-thumb"></span></span
                  ></label>
                </div>
              </div>
              <div class="ep-field">
                <div class="ep-toggle-row">
                  <div>
                    <div class="ep-toggle-title">System Alerts</div>
                    <div class="ep-toggle-desc">
                      Important system and security notifications
                    </div>
                  </div>
                  <label class="ep-switch"
                    ><input type="checkbox" checked /><span
                      class="ep-switch-track"
                      ><span class="ep-switch-thumb"></span></span
                  ></label>
                </div>
              </div>
            </div>
            <div class="ep-form-actions">
              <button class="ep-btn ep-btn--ghost" type="button">
                Reset to Defaults
              </button>
              <button
                class="ep-btn ep-btn--primary"
                type="button"
                onclick="showToast('✅ Notification preferences saved!')"
              >
                Save Preferences
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- /ep-panels -->
    </main>

    <!-- ══ ADD USER MODAL ══ -->
    <div class="ep-modal-overlay hidden" id="addModal">
      <div class="ep-modal">
        <div class="ep-modal__header">
          <h3>➕ Add New User</h3>
          <button class="ep-modal__close" id="closeModal">✕</button>
        </div>
        <div class="ep-modal__body">
          <div class="ep-field-row">
            <div class="ep-field">
              <label>First Name *</label>
              <input type="text" id="newFirst" placeholder="First name" />
            </div>
            <div class="ep-field">
              <label>Last Name *</label>
              <input type="text" id="newLast" placeholder="Last name" />
            </div>
          </div>
          <div class="ep-field">
            <label>Email Address *</label>
            <input
              type="email"
              id="newEmail"
              placeholder="email@wellucation.edu"
            />
          </div>
          <div class="ep-field-row">
            <div class="ep-field">
              <label>Password *</label>
              <input
                type="password"
                id="newPassword"
                autocomplete="new-password"
                placeholder="Enter a password"
              />
            </div>
            <div class="ep-field">
              <label>Role *</label>
              <select id="newRole">
                <option value="teacher">👩‍🏫 Teacher</option>
                <option value="admin">🛡️ Admin</option>
                <option value="parent">❤️ Parent</option>
                <option value="child">👶 Child</option>
              </select>
            </div>
          </div>
          <div class="ep-field-row">
            <div class="ep-field">
              <label>Class</label>
              <input type="text" id="newClass" placeholder="e.g. KG1" />
            </div>
          </div>
          <div class="ep-field">
            <label>Status</label>
            <select id="newStatus">
              <option value="active">Active</option>
              <option value="pending">Pending</option>
            </select>
          </div>
        </div>
        <div class="ep-modal__footer">
          <button class="ep-btn ep-btn--ghost" id="cancelModal">Cancel</button>
          <button class="ep-btn ep-btn--primary" id="confirmAdd">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="15"
              height="15"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M5 12h14" />
              <path d="M12 5v14" />
            </svg>
            Add User
          </button>
        </div>
      </div>
    </div>

    <!-- ══ DELETE CONFIRM MODAL ══ -->
    <div class="ep-modal-overlay hidden" id="deleteModal">
      <div class="ep-modal ep-modal--sm">
        <div class="ep-modal__header">
          <h3>🗑️ Confirm Delete</h3>
          <button class="ep-modal__close" id="closeDeleteModal">✕</button>
        </div>
        <div class="ep-modal__body">
          <p id="deleteModalMsg">Are you sure you want to remove this user?</p>
          <p style="margin-top: 0.5rem; font-size: 0.8125rem; color: #9ca3af">
            This action cannot be undone.
          </p>
        </div>
        <div class="ep-modal__footer">
          <button class="ep-btn ep-btn--ghost" id="cancelDelete">Cancel</button>
          <button class="ep-btn ep-btn--danger" id="confirmDelete">
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- ══ TOAST ══ -->
    <div class="ep-toast hidden" id="toast"></div>

    <script src="
	scripts/EditProfile.js"></script>
  </body>
</html>
