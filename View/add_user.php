<?php
session_start();

// Check if user is admin (for demo, bypass this check)
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     exit('Forbidden - Admin access required');
// }

// Display form for GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pageTitle = "Add User – Wellucation Nursery";
    $currentPage = "add_user";
    $pageCss = 'add_user.css';
    include 'header.php';
    include 'navbar.php';
    ?>
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
              <label class="form-label">Full Name *</label>
              <input type="text" name="name" required class="form-input" placeholder="John Doe">
            </div>
            <div class="form-group">
              <label class="form-label">Email *</label>
              <input type="email" name="email" required class="form-input" placeholder="john@example.com">
            </div>
            <div class="form-group">
              <label class="form-label">Role *</label>
              <select name="role" required class="form-select">
                <option value="">Select a role</option>
                <option>Student</option>
                <option>Teacher</option>
                <option>Parent</option>
                <option>Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Class (for students)</label>
              <input type="text" name="class" class="form-input" placeholder="e.g., KG1">
            </div>
            <div class="add-user-actions">
              <button type="submit" class="btn btn-primary">✅ Add User</button>
              <a href="dashboard.php" class="btn btn-link">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </section>
    <?php
    include 'footer.php';
    exit;
}

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $role = htmlspecialchars($_POST['role'] ?? '');
    $class = htmlspecialchars($_POST['class'] ?? '');
    
    // Validation
    if (!$name || !$email || !$role) {
        $_SESSION['error'] = 'Please fill in all required fields';
        header('Location: add_user.php');
        exit;
    }

    if (strlen($name) < 2) {
        $_SESSION['error'] = 'Name must be at least 2 characters';
        header('Location: add_user.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter a valid email address';
        header('Location: add_user.php');
        exit;
    }
    
    // In production, insert into database
    // $stmt = $pdo->prepare("INSERT INTO users (name, email, role, class) VALUES (?, ?, ?, ?)");
    // $stmt->execute([$name, $email, $role, $class]);
    
    // Log the action
    error_log("New user added: $name ($email) as $role");
    
    $_SESSION['message'] = "User $name added successfully!";
    header('Location: dashboard.php');
    exit;
}
?>
