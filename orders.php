<?php
// orders.php — lists all orders
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/db.php';
include 'includes/header.php';

// Fetch orders
$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">📦 Recent Orders</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php $items = json_decode($row['items'], true); ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['fullname']); ?></h5>
          <p class="text-muted small mb-1"><?php echo htmlspecialchars($row['phone']); ?></p>
          <p class="mb-2"><?php echo nl2br(htmlspecialchars($row['address'])); ?></p>

          <table class="table table-sm">
            <thead>
              <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
            </thead>
            <tbody>
            <?php
            $total = 0.0;
            foreach ($items as $it):
              $qty = $it['quantity'] ?? $it['qty'] ?? 1;
              $price = floatval($it['price'] ?? 0);
              $line = $qty * $price;
              $total += $line;
            ?>
              <tr>
                <td><?php echo htmlspecialchars($it['name']); ?></td>
                <td><?php echo $qty; ?></td>
                <td>₹<?php echo number_format($price,2); ?></td>
                <td>₹<?php echo number_format($line,2); ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>

          <div class="text-end">
            <strong>Grand Total: ₹<?php echo number_format($total, 2); ?></strong>
          </div>

          <p class="text-muted small mt-2">Ordered on: <?php echo htmlspecialchars($row['order_date']); ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-info">No orders found yet.</div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
