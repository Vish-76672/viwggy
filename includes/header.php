<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Viwggy — Order Food Online</title>
  <!-- Bootstrap (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img src="assets/img/logo.png" alt="Viwggy" height="36">
      <span class="fw-bold text-warning">Viwggy</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navCollapse">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item me-2"><a class="nav-link" href="restaurant.php">Restaurants</a></li>
        <li class="nav-item me-2"><a class="nav-link" href="cart.php">Cart
          <?php
            $count = 0;
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                $count = count($_SESSION['cart']);
            }
            if ($count > 0) echo " (<span class='text-danger'>$count</span>)";
          ?>
        </a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="btn btn-warning rounded-pill ms-2" href="signup.php">Sign up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
