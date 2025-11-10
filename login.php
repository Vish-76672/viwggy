<?php
// login.php
include('includes/header.php');
include('includes/db.php');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
    if (empty($password)) $errors[] = "Enter your password.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($uid, $uname, $phash);
        if ($stmt->fetch()) {
            if (password_verify($password, $phash)) {
                // login success
                $_SESSION['user_id'] = $uid;
                $_SESSION['user_name'] = $uname;
                $stmt->close();
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Email or password incorrect.";
            }
        } else {
            $errors[] = "Email or password incorrect.";
        }
        if ($stmt) $stmt->close();
    }
}
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">Login to Viwggy</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?></div>
  <?php endif; ?>

  <form method="POST" class="col-md-5">
    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div>
      <button class="btn btn-warning rounded-pill">Login</button>
      <a href="signup.php" class="btn btn-link">Create account</a>
    </div>
  </form>
</div>

<?php include('includes/footer.php'); ?>
