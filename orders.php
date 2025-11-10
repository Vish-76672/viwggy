<?php
// orders.php
include('includes/header.php');
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = intval($_SESSION['user_id']);
$stmt = $conn->prepare("SELECT id, items, total, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4">My Orders</h2>

  <?php if ($result->num_rows == 0): ?>
    <div class="alert alert-info">You have no past orders.</div>
  <?php else: ?>
    <div class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="list-group-item">
          <div class="d-flex justify-content-between">
            <div>
              <strong>Order #<?php echo intval($row['id']); ?></strong>
              <div class="text-muted small"><?php echo $row['order_date']; ?></div>
            </div>
            <div class="text-end">
              <div class="fw-bold">₹<?php echo number_format($row['total'],2); ?></div>
            </div>
          </div>
          <div class="mt-2 small">
            <?php
              $items = json_decode($row['items'], true);
              if (is_array($items)) {
                foreach ($items as $it) {
                  echo htmlspecialchars($it['name'])." x".intval($it['qty'])." — ₹".number_format($it['price']*$it['qty'],2)."<br>";
                }
              }
            ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<?php
$stmt->close();
include('includes/footer.php');
?>
