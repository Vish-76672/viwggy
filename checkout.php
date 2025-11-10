<?php
// checkout.php — creates an order from the session cart
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db.php';
include 'includes/header.php';

// redirect if cart empty
if (empty($_SESSION['cart'])) {
    echo '<div class="container py-5"><div class="alert alert-warning">Your cart is empty. <a href="restaurant.php">Go back</a>.</div></div>';
    include 'includes/footer.php';
    exit();
}

$orderSuccess = false;
$errorMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');

    if ($fullname && $phone && $address) {
        $cartItems = $_SESSION['cart'];
        $itemsJson = json_encode($cartItems, JSON_UNESCAPED_UNICODE);
        $total = 0.0;
        foreach ($cartItems as $it) {
            $qty = $it['quantity'] ?? $it['qty'] ?? 1;
            $total += ($it['price'] ?? 0) * $qty;
        }

        // Use prepared statement for security
        $stmt = $conn->prepare("INSERT INTO orders (fullname, phone, address, items, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $fullname, $phone, $address, $itemsJson, $total);

        if ($stmt->execute()) {
            $orderSuccess = true;
            $_SESSION['cart'] = []; // clear cart
        } else {
            $errorMsg = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $errorMsg = "Please fill all fields.";
    }
}
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">🧾 Checkout</h2>

  <?php if ($orderSuccess): ?>
    <div class="alert alert-success">
      Order placed successfully! 🎉
      <a href="orders.php" class="alert-link">View Orders</a>
    </div>
  <?php elseif ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
  <?php endif; ?>

  <?php if (!$orderSuccess): ?>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="fullname" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone Number</label>
      <input type="text" name="phone" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Delivery Address</label>
      <textarea name="address" rows="3" class="form-control" required></textarea>
    </div>

    <h5 class="mt-4">Order Summary</h5>
    <ul class="list-group mb-3">
      <?php
      $grand_total = 0.0;
      foreach ($_SESSION['cart'] as $it):
        $qty = $it['quantity'] ?? $it['qty'] ?? 1;
        $price = floatval($it['price'] ?? 0);
        $line_total = $qty * $price;
        $grand_total += $line_total;
      ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?php echo htmlspecialchars($it['name']); ?> × <?php echo $qty; ?>
          <span>₹<?php echo number_format($line_total, 2); ?></span>
        </li>
      <?php endforeach; ?>
      <li class="list-group-item d-flex justify-content-between">
        <strong>Total:</strong>
        <strong>₹<?php echo number_format($grand_total, 2); ?></strong>
      </li>
    </ul>

    <button type="submit" class="btn btn-success w-100 rounded-pill">Place Order</button>
  </form>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
