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

// Display form for GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch active courses to populate class dropdown
    $courses = Database::getInstance()->fetchAll("SELECT courseID, name FROM course WHERE isActive = 1 ORDER BY name ASC");
    if (!$courses) {
        $courses = [];
    }
    $pageTitle = "Add User – Wellucation Nursery";
    $currentPage = "add_user";
    $pageCss = 'add_user.css';
    include 'header.php';
    include 'navbar.php';
    ?>
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($pageTitle); ?></title>
	<link rel="stylesheet" href="css/home.css" />
	<link rel="stylesheet" href="add_user.css" />
	<link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
    <section class="page-hero">
      <div class="page-hero__content add-user-hero__content">
        <h1 class="page-hero__title">Add New User</h1>
        <p class="page-hero__subtitle">Create a new user account for the system</p>
      </div>
    </section>
    
    <section class="section section--gray">
      <div class="container container-narrow">
        <div class="add-user-panel">
          <form method="POST" action="add_user.php">
            <div class="form-group">
              <label class="form-label">First Name <span class="required">*</span></label>
              <input type="text" name="first_name" required class="form-input" placeholder="John">
            </div>
            <div class="form-group">
              <label class="form-label">Last Name <span class="required">*</span></label>
              <input type="text" name="last_name" required class="form-input" placeholder="Doe">
            </div>
            <div class="form-group">
              <label class="form-label">Date of Birth <span class="required">*</span></label>
              <input type="date" name="date_of_birth" required class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Gender <span class="required">*</span></label>
              <select name="gender" required class="form-select">
                <option value="">Select a gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-input" placeholder="student@example.com">
            </div>
            <div class="form-group">
              <label class="form-label">Class/Grade <span class="required">*</span></label>
              <select name="class" id="class" required class="form-select">
                <option value="">Select a class</option>
                <?php foreach ($courses as $course): ?>
                  <option value="<?php echo htmlspecialchars($course['courseID']); ?>">
                    <?php echo htmlspecialchars($course['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Enrollment Date <span class="required">*</span></label>
              <input type="date" name="enrollment_date" required class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Parent/Guardian Name</label>
              <input type="text" name="parent_name" class="form-input" placeholder="Parent or guardian name">
            </div>
            <div class="form-group">
              <label class="form-label">Parent/Guardian Email</label>
              <input type="email" name="parent_email" class="form-input" placeholder="parent@example.com">
            </div>
            <div class="form-group">
              <label class="form-label">Parent/Guardian Phone</label>
              <input type="tel" name="parent_phone" class="form-input" placeholder="+1 (555) 000-0000">
            </div>
            <div class="add-user-actions">
              <button type="submit" class="btn btn-primary">✅ Add Student</button>
              <a href="dashboard.php" class="btn btn-link">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </section>
    <?php
    include 'footer.php';
    ?>
    <script src="add_user.js"></script>
    <?php
    exit;
}

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = htmlspecialchars($_POST['first_name'] ?? '');
    $last_name = htmlspecialchars($_POST['last_name'] ?? '');
    $date_of_birth = htmlspecialchars($_POST['date_of_birth'] ?? '');
    $gender = htmlspecialchars($_POST['gender'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $class = htmlspecialchars($_POST['class'] ?? '');
    $enrollment_date = htmlspecialchars($_POST['enrollment_date'] ?? '');
    $parent_name = htmlspecialchars($_POST['parent_name'] ?? '');
    $parent_email = filter_var($_POST['parent_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $parent_phone = htmlspecialchars($_POST['parent_phone'] ?? '');
    
    // Validation
    if (!$first_name || !$last_name || !$date_of_birth || !$gender || !$class || !$enrollment_date) {
        $_SESSION['error'] = 'Please fill in all required fields';
        header('Location: add_user.php');
        exit;
    }

    if (strlen($first_name) < 2 || strlen($last_name) < 2) {
        $_SESSION['error'] = 'First and last names must be at least 2 characters';
        header('Location: add_user.php');
        exit;
    }

    if (!strtotime($date_of_birth)) {
        $_SESSION['error'] = 'Please enter a valid date of birth';
        header('Location: add_user.php');
        exit;
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter a valid email address';
        header('Location: add_user.php');
        exit;
    }
    
    // Add child using the Child model's AddChild method
    $childData = [
        'Name' => $first_name . ' ' . $last_name,
        'DateOfBirth' => $date_of_birth,
        'Gender' => $gender,
        'EmergencyContact' => $parent_phone,
        'enrollmentStatus' => 'Active'
    ];
    
    try {
        $childId = Child::AddChild($childData);
        if ($childId) {
            // Enroll student in the selected course
            if (!empty($class)) {
                $enrollmentSql = "INSERT INTO Enrollment (childID, courseID, enrollmentDate, status) 
                                 VALUES (?, ?, ?, ?)";
                $enrollmentParams = [$childId, $class, $enrollment_date, 'Active'];
                Database::getInstance()->query($enrollmentSql, $enrollmentParams);
            }
            
            $_SESSION['message'] = "Student $first_name $last_name added successfully!";
            error_log("New student added: $first_name $last_name (DOB: $date_of_birth) - Child ID: $childId");
            header('Location: dashboard.php');
            exit;
        } else {
            throw new Exception("Failed to insert student into database");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error adding student: " . $e->getMessage();
        error_log("Error adding student: " . $e->getMessage());
        header('Location: add_user.php');
        exit;
    }
}
?>
