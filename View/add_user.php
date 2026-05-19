<?php
session_start();
require_once '../Models/Database.php';
require_once '../Models/Child.php';
require_once '../Models/Course.php';

// Check if user is admin (for demo, bypass this check)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Forbidden - Admin access required');
// }

require_once __DIR__ . '/../Controller/DashboardController.php';
$controller = new DashboardController();

function flashError(string $message, array $old = []) {
    $_SESSION['error'] = $message;
    $_SESSION['old'] = $old;
    header('Location: add_user.php');
    exit;
}

function getPost(string $key): string {
    return trim($_POST[$key] ?? '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = getPost('name');
    $email = getPost('email');
    $role = getPost('role');
    $class = getPost('class');

    $dob = getPost('dob');
    $gender = getPost('gender');
    $allergies = getPost('allergies');
    $emergencyContact = getPost('emergency_contact');
    $parentName = getPost('parent_name');
    $parentEmail = getPost('parent_email');
    $parentPhone = getPost('parent_phone');
    $parentAddress = getPost('parent_address');

    $teacherPhone = getPost('teacher_phone');
    $specialization = getPost('specialization');
    $qualifications = getPost('qualifications');

    $parentProfilePhone = getPost('parent_profile_phone');
    $parentProfileAddress = getPost('parent_profile_address');

    $old = [
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'class' => $class,
        'dob' => $dob,
        'gender' => $gender,
        'allergies' => $allergies,
        'emergency_contact' => $emergencyContact,
        'parent_name' => $parentName,
        'parent_email' => $parentEmail,
        'parent_phone' => $parentPhone,
        'parent_address' => $parentAddress,
        'teacher_phone' => $teacherPhone,
        'specialization' => $specialization,
        'qualifications' => $qualifications,
        'parent_profile_phone' => $parentProfilePhone,
        'parent_profile_address' => $parentProfileAddress
    ];

    if (!$name || !$email || !$role) {
        flashError('Please fill in all required fields.', $old);
    }

    if (strlen($name) < 2) {
        flashError('Name must be at least 2 characters.', $old);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flashError('Please enter a valid email address.', $old);
    }

    if ($role === 'Student') {
        if (!$dob || !$gender || !$parentName || !$parentEmail || !$parentPhone) {
            flashError('Student entries require the child date of birth and parent contact details.', $old);
        }
        if (!filter_var($parentEmail, FILTER_VALIDATE_EMAIL)) {
            flashError('Please enter a valid parent email address.', $old);
        }
    }

    if ($role === 'Teacher' && !$specialization) {
        flashError('Please add a teacher specialization.', $old);
    }

    if ($role === 'Parent' && !$parentProfilePhone) {
        flashError('Please add a phone number for the parent profile.', $old);
    }

    try {
        $controller->addUser($_POST);
        $_SESSION['message'] = "User $name added successfully!";
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        error_log('Add user failed: ' . $e->getMessage());
        flashError('Unable to create the user. Please try again. ' . $e->getMessage(), $old);
    }
}

$old = array_merge(
    [
        'name' => '',
        'email' => '',
        'role' => '',
        'class' => '',
        'dob' => '',
        'gender' => '',
        'allergies' => '',
        'emergency_contact' => '',
        'parent_name' => '',
        'parent_email' => '',
        'parent_phone' => '',
        'parent_address' => '',
        'teacher_phone' => '',
        'specialization' => '',
        'qualifications' => '',
        'parent_profile_phone' => '',
        'parent_profile_address' => ''
    ],
    $_SESSION['old'] ?? []
);

$flashMessage = $_SESSION['message'] ?? null;
$flashError = $_SESSION['error'] ?? null;
unset($_SESSION['error'], $_SESSION['message'], $_SESSION['old']);

$pageTitle = "Add User – Wellucation Nursery";
$currentPage = "add_user";
$pageCss = 'add_user.css';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
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
      <h1 class="page-hero__title">Add New User</h1>
      <p class="page-hero__subtitle">Create a new user account for the Wellucation system.</p>
    </div>
  </section>

  <section class="section section--gray">
    <div class="container container-narrow">
      <?php if ($flashError): ?>
        <div class="alert-banner alert-banner--error"><?php echo htmlspecialchars($flashError); ?></div>
      <?php endif; ?>
      <?php if ($flashMessage): ?>
        <div class="alert-banner alert-banner--success"><?php echo htmlspecialchars($flashMessage); ?></div>
      <?php endif; ?>

      <div class="add-user-panel">
        <form method="POST" action="add_user.php" class="user-form">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Full Name *</label>
              <input id="name" type="text" name="name" required value="<?php echo htmlspecialchars($old['name']); ?>" class="form-input" placeholder="John Doe" />
            </div>
            <div class="form-group">
              <label for="email">Email *</label>
              <input id="email" type="email" name="email" required value="<?php echo htmlspecialchars($old['email']); ?>" class="form-input" placeholder="john@example.com" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="role">Role *</label>
              <select id="role" name="role" required class="form-input form-select">
                <option value="">Select a role</option>
                <option value="Student" <?php echo $old['role'] === 'Student' ? 'selected' : ''; ?>>Student</option>
                <option value="Teacher" <?php echo $old['role'] === 'Teacher' ? 'selected' : ''; ?>>Teacher</option>
                <option value="Parent" <?php echo $old['role'] === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                <option value="Admin" <?php echo $old['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label for="class">Class (for students)</label>
              <input id="class" type="text" name="class" value="<?php echo htmlspecialchars($old['class']); ?>" class="form-input" placeholder="e.g., KG1" />
            </div>
          </div>

          <div class="role-section" id="studentSection">
            <h2 class="section-heading">Student Details</h2>
            <div class="form-row">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input id="dob" type="date" name="dob" value="<?php echo htmlspecialchars($old['dob']); ?>" class="form-input" />
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="form-input form-select">
                  <option value="">Select gender</option>
                  <option value="M" <?php echo $old['gender'] === 'M' ? 'selected' : ''; ?>>Male</option>
                  <option value="F" <?php echo $old['gender'] === 'F' ? 'selected' : ''; ?>>Female</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="allergies">Allergies / Notes</label>
                <textarea id="allergies" name="allergies" rows="3" class="form-input"><?php echo htmlspecialchars($old['allergies']); ?></textarea>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="emergency_contact">Emergency Contact</label>
                <input id="emergency_contact" type="text" name="emergency_contact" value="<?php echo htmlspecialchars($old['emergency_contact']); ?>" class="form-input" placeholder="+966 5X XXX XXXX" />
              </div>
            </div>

            <h3 class="section-subheading">Parent / Guardian Info</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="parent_name">Parent Name</label>
                <input id="parent_name" type="text" name="parent_name" value="<?php echo htmlspecialchars($old['parent_name']); ?>" class="form-input" placeholder="Jane Doe" />
              </div>
              <div class="form-group">
                <label for="parent_email">Parent Email</label>
                <input id="parent_email" type="email" name="parent_email" value="<?php echo htmlspecialchars($old['parent_email']); ?>" class="form-input" placeholder="parent@example.com" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="parent_phone">Parent Phone</label>
                <input id="parent_phone" type="tel" name="parent_phone" value="<?php echo htmlspecialchars($old['parent_phone']); ?>" class="form-input" placeholder="+966 5X XXX XXXX" />
              </div>
              <div class="form-group full-width">
                <label for="parent_address">Parent Address</label>
                <textarea id="parent_address" name="parent_address" rows="2" class="form-input"><?php echo htmlspecialchars($old['parent_address']); ?></textarea>
              </div>
            </div>
          </div>

          <div class="role-section" id="teacherSection">
            <h2 class="section-heading">Teacher Details</h2>
            <div class="form-row">
              <div class="form-group">
                <label for="teacher_phone">Phone</label>
                <input id="teacher_phone" type="tel" name="teacher_phone" value="<?php echo htmlspecialchars($old['teacher_phone']); ?>" class="form-input" placeholder="+966 5X XXX XXXX" />
              </div>
              <div class="form-group">
                <label for="specialization">Specialization</label>
                <input id="specialization" type="text" name="specialization" value="<?php echo htmlspecialchars($old['specialization']); ?>" class="form-input" placeholder="e.g., Early Childhood Education" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="qualifications">Qualifications</label>
                <textarea id="qualifications" name="qualifications" rows="3" class="form-input"><?php echo htmlspecialchars($old['qualifications']); ?></textarea>
              </div>
            </div>
          </div>

          <div class="role-section" id="parentSection">
            <h2 class="section-heading">Parent Profile Details</h2>
            <div class="form-row">
              <div class="form-group">
                <label for="parent_profile_phone">Phone</label>
                <input id="parent_profile_phone" type="tel" name="parent_profile_phone" value="<?php echo htmlspecialchars($old['parent_profile_phone']); ?>" class="form-input" placeholder="+966 5X XXX XXXX" />
              </div>
              <div class="form-group full-width">
                <label for="parent_profile_address">Address</label>
                <textarea id="parent_profile_address" name="parent_profile_address" rows="2" class="form-input"><?php echo htmlspecialchars($old['parent_profile_address']); ?></textarea>
              </div>
            </div>
          </div>

          <div class="role-section" id="adminSection">
            <h2 class="section-heading">Admin Details</h2>
            <p class="helper-text">Admins do not require additional profile details beyond the user account.</p>
          </div>

          <div class="add-user-actions">
            <button type="submit" class="btn btn-primary">✅ Add User</button>
            <a href="dashboard.php" class="btn btn-link">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<script>
  const roleSelect = document.getElementById('role');
  const sections = {
    Student: document.getElementById('studentSection'),
    Teacher: document.getElementById('teacherSection'),
    Parent: document.getElementById('parentSection'),
    Admin: document.getElementById('adminSection')
  };

  function updateRoleSections() {
    const selectedRole = roleSelect.value;
    Object.entries(sections).forEach(([role, section]) => {
      section.style.display = selectedRole === role ? 'block' : 'none';
    });
  }

  roleSelect.addEventListener('change', updateRoleSections);
  updateRoleSections();
</script>
</body>
</html>

