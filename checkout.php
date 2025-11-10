<?php
// checkout.php
include('includes/header.php');
include('includes/db.php');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// If user is logged in, prefill some fields (optional)
$userName = '';
$userPhone = '';
if (isset($_SESSION['user_id'])) {
    // could fetch more from users table if stored
    $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
}
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">Checkout</h2>

  <form action="order-success.php" method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="fullname" required value="<?php echo htmlspecialchars($userName); ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input type="tel" class="form-control" name="phone" required value="<?php echo htmlspecialchars($userPhone); ?>">
    </div>
    <div class="col-12">
      <label class="form-label">Delivery Address</label>
      <textarea class="form-control" name="address" rows="3" required></textarea>
    </div>

    <div class="col-12 text-end">
      <button type="submit" class="btn btn-warning rounded-pill px-4">Place Order</button>
    </div>
  </form>
</div>

<?php include('includes/footer.php'); ?>
