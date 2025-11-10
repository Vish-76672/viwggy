<?php
// cart.php
include('includes/header.php');
include('includes/db.php');

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Remove item by index
if (isset($_GET['remove'])) {
    $idx = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$idx])) {
        unset($_SESSION['cart'][$idx]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: cart.php");
    exit;
}

// Update quantities (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $i => $q) {
        $q = intval($q);
        if ($q <= 0) {
            unset($_SESSION['cart'][$i]);
        } else {
            $_SESSION['cart'][$i]['qty'] = $q;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">🛒 Your Cart</h2>

  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">Your cart is empty. <a href="index.php" class="alert-link">Browse restaurants</a>.</div>
  <?php else: ?>
    <form method="POST">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Item</th>
            <th>Price (₹)</th>
            <th style="width:120px;">Quantity</th>
            <th>Total (₹)</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $grand_total = 0.0;
            foreach($_SESSION['cart'] as $i => $it):
              $total = $it['price'] * $it['qty'];
              $grand_total += $total;
            ?>
            <tr>
              <td><?php echo htmlspecialchars($it['name']); ?></td>
              <td><?php echo number_format($it['price'],2); ?></td>
              <td>
                <input type="number" name="qty[<?php echo $i; ?>]" class="form-control" min="0" value="<?php echo $it['qty']; ?>">
              </td>
              <td><?php echo number_format($total,2); ?></td>
              <td><a class="btn btn-sm btn-danger" href="cart.php?remove=<?php echo $i; ?>">Remove</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center">
        <div>
          <button type="submit" name="update_cart" class="btn btn-outline-primary">Update Cart</button>
        </div>
        <div class="text-end">
          <h4 class="fw-bold">Grand Total: ₹<?php echo number_format($grand_total,2); ?></h4>
          <a href="checkout.php" class="btn btn-success rounded-pill mt-2">Proceed to Checkout</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
