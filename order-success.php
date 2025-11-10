<?php
// order-success.php
include('includes/header.php');
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='container py-5'><div class='alert alert-warning'>No items in cart.</div></div>";
    include('includes/footer.php');
    exit;
}

$fullname = trim($_POST['fullname']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// calculate total
$total = 0.0;
foreach ($_SESSION['cart'] as $it) {
    $total += ($it['price'] * $it['qty']);
}

// items as JSON
$items_json = json_encode($_SESSION['cart'], JSON_UNESCAPED_UNICODE);

// associate user if logged in
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

// insert into orders
$stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, phone, address, items, total) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("issssd", $user_id, $fullname, $phone, $address, $items_json, $total);
$executed = $stmt->execute();
if (!$executed) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}
$order_id = $stmt->insert_id;
$stmt->close();

// Optionally store in user_orders table, send email, etc.

// clear cart
$_SESSION['cart'] = [];

?>

<div class="container text-center py-5">
  <h1 class="text-success fw-bold">🎉 Order Placed Successfully!</h1>
  <p class="lead mt-3">Thank you, <strong><?php echo htmlspecialchars($fullname); ?></strong>.<br>
    Your order #<?php echo intval($order_id); ?> has been placed. Total: <strong>₹<?php echo number_format($total,2); ?></strong></p>
  <a href="index.php" class="btn btn-warning mt-4">Back to Home</a>
</div>

<?php include('includes/footer.php'); ?>
