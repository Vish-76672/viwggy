<?php
// signup.php
include('includes/header.php');
include('includes/db.php');

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (strlen($name) < 2) $errors[] = "Enter a valid name.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $password2) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        // check email uniqueness
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered. Try logging in.";
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            if ($stmt->execute()) {
                $success = "Account created successfully. You can login now.";
                // Auto-login after signup (optional)
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Signup failed. Try again later.";
            }
        }
        if ($stmt) $stmt->close();
    }
}
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">Create an account</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?php foreach($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-3 col-md-6">
    <div class="col-12">
      <label class="form-label">Full name</label>
      <input type="text" name="name" class="form-control" required value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
    </div>
    <div class="col-12">
      <label class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Confirm password</label>
      <input type="password" name="password2" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-warning rounded-pill">Sign up</button>
      <a href="login.php" class="btn btn-link">Already have an account? Login</a>
    </div>
  </form>
</div>

<?php include('includes/footer.php'); ?>
