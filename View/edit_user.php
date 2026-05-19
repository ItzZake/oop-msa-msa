<?php
session_start();
require_once __DIR__ . '/../Controller/DashboardController.php';
require_once __DIR__ . '/../Models/Database.php';
$controller = new DashboardController();
$db = Database::getInstance();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    $_SESSION['error'] = 'Invalid user id.';
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    try {
        $controller->editUser($id, $data);
        $_SESSION['message'] = 'User updated successfully.';
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        error_log('Edit user failed: ' . $e->getMessage());
        $_SESSION['error'] = 'Unable to update user. ' . $e->getMessage();
    }
}

$user = $db->fetchOne('SELECT userID, email, firstname, Lastname, Role FROM `User` WHERE userID = ? LIMIT 1', [$id]);
if (!$user) {
    $_SESSION['error'] = 'User not found.';
    header('Location: dashboard.php');
    exit;
}

$role = $user['Role'] ?? '';
$profile = [];
if ($role === 'Teacher') {
    $profile = $db->fetchOne('SELECT phone, qualifications, specialization FROM `Teacher` WHERE userID = ? LIMIT 1', [$id]) ?? [];
} elseif ($role === 'Parent') {
    $profile = $db->fetchOne('SELECT phone, address FROM `Parent` WHERE userID = ? LIMIT 1', [$id]) ?? [];
}

function esc($s) { return htmlspecialchars($s ?? ''); }

$pageTitle = 'Edit User';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo esc($pageTitle); ?></title>
  <link rel="stylesheet" href="../view/css/add_user.css" />
  <link rel="stylesheet" href="../view/css/navbar.css" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet" />
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<main class="main-content">
  <section class="page-hero">
    <div class="page-hero__content add-user-hero__content">
      <span class="page-badge">Admin</span>
      <h1 class="page-hero__title">Edit User</h1>
      <p class="page-hero__subtitle">Update account details and profile settings for this user.</p>
    </div>
  </section>
  <section class="section section--gray">
    <div class="container container-narrow">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-banner alert-banner--error"><?php echo esc($_SESSION['error']); unset($_SESSION['error']); ?></div>
      <?php endif; ?>
      <div class="add-user-panel">
        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>">
          <div class="form-row">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" value="<?php echo esc($user['email']); ?>" class="form-input" />
            </div>
            <div class="form-group">
              <label>First name</label>
              <input type="text" name="firstname" value="<?php echo esc($user['firstname']); ?>" class="form-input" />
            </div>
            <div class="form-group">
              <label>Last name</label>
              <input type="text" name="Lastname" value="<?php echo esc($user['Lastname']); ?>" class="form-input" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group full-width">
              <label>Role</label>
              <select id="role" name="Role" class="form-input">
                <option value="Child" <?php echo $role === 'Child' ? 'selected' : ''; ?>>Student</option>
                <option value="Teacher" <?php echo $role === 'Teacher' ? 'selected' : ''; ?>>Teacher</option>
                <option value="Parent" <?php echo $role === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Admin</option>
              </select>
            </div>
          </div>

          <div class="role-section" id="teacherSection" style="display: <?php echo $role === 'Teacher' ? 'block' : 'none'; ?>;">
            <h2 class="section-heading">Teacher Profile</h2>
            <p class="section-subheading">Update the teacher's contact and professional details.</p>
            <div class="form-row">
              <div class="form-group">
                <label>Phone</label>
                <input name="teacher_phone" value="<?php echo esc($profile['phone'] ?? ''); ?>" class="form-input" />
              </div>
              <div class="form-group">
                <label>Specialization</label>
                <input name="specialization" value="<?php echo esc($profile['specialization'] ?? ''); ?>" class="form-input" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label>Qualifications</label>
                <textarea name="qualifications" class="form-input"><?php echo esc($profile['qualifications'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>

          <div class="role-section" id="parentSection" style="display: <?php echo $role === 'Parent' ? 'block' : 'none'; ?>;">
            <h2 class="section-heading">Parent Profile</h2>
            <p class="section-subheading">Update contact details for this parent or guardian.</p>
            <div class="form-row">
              <div class="form-group">
                <label>Phone</label>
                <input name="parent_profile_phone" value="<?php echo esc($profile['phone'] ?? ''); ?>" class="form-input" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label>Address</label>
                <textarea name="parent_profile_address" class="form-input"><?php echo esc($profile['address'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>

          <div class="role-section" id="adminSection" style="display: <?php echo $role === 'Admin' ? 'block' : 'none'; ?>;">
            <h2 class="section-heading">Admin Profile</h2>
            <p class="section-subheading">Admin accounts do not require additional profile details.</p>
          </div>

          <div class="add-user-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="dashboard.php" class="btn btn-link">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>
        <script>
          const roleSelect = document.getElementById('role');
          const editSections = {
            Teacher: document.getElementById('teacherSection'),
            Parent: document.getElementById('parentSection'),
            Admin: document.getElementById('adminSection'),
            Child: null
          };

          function updateEditSections() {
            const selectedRole = roleSelect.value;
            Object.entries(editSections).forEach(([role, section]) => {
              if (!section) return;
              section.style.display = selectedRole === role ? 'block' : 'none';
            });
          }

          roleSelect.addEventListener('change', updateEditSections);
          updateEditSections();
        </script>
<?php include 'footer.php'; ?>
</body>
</html>
