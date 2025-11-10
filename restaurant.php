<?php
// restaurant.php
include('includes/header.php');
include('includes/db.php');

// For demo: simple menu list per restaurant id.
// In production you'd load restaurants and menu from DB.
$restaurantId = isset($_GET['id']) ? intval($_GET['id']) : 2;
$restaurantName = "Pizza Planet";
if ($restaurantId == 1) $restaurantName = "Burger Hub";
if ($restaurantId == 3) $restaurantName = "Healthy Bowl";

$foods = [
  ['id'=>101,'name'=>'Margherita Pizza','price'=>199,'img'=>'assets/img/food1.jpg'],
  ['id'=>102,'name'=>'Pepperoni Pizza','price'=>249,'img'=>'assets/img/food2.jpg'],
  ['id'=>103,'name'=>'Veggie Supreme','price'=>229,'img'=>'assets/img/food3.jpg'],
  ['id'=>104,'name'=>'Garlic Bread','price'=>79,'img'=>'assets/img/food4.jpg'],
];

// Handle add-to-cart (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $fid = intval($_POST['food_id']);
    $fname = trim($_POST['food_name']);
    $fprice = floatval($_POST['food_price']);
    $found_index = null;
    // if item exists increment qty
    foreach ($_SESSION['cart'] as $i => $it) {
        if (isset($it['id']) && $it['id'] == $fid) {
            $found_index = $i;
            break;
        }
    }
    if ($found_index !== null) {
        $_SESSION['cart'][$found_index]['qty'] += 1;
    } else {
        $_SESSION['cart'][] = ['id'=>$fid, 'name'=>$fname, 'price'=>$fprice, 'qty'=>1];
    }
    header("Location: cart.php");
    exit;
}
?>

<div class="container py-5">
  <h2 class="fw-bold mb-4"><?php echo htmlspecialchars($restaurantName); ?></h2>
  <div class="row">
    <?php foreach($foods as $f): ?>
      <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
          <img src="<?php echo $f['img']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($f['name']); ?>">
          <div class="card-body text-center">
            <h6 class="fw-semibold"><?php echo htmlspecialchars($f['name']); ?></h6>
            <p class="mb-2">₹<?php echo number_format($f['price'], 2); ?></p>

            <form method="POST" class="d-inline">
              <input type="hidden" name="food_id" value="<?php echo $f['id']; ?>">
              <input type="hidden" name="food_name" value="<?php echo htmlspecialchars($f['name']); ?>">
              <input type="hidden" name="food_price" value="<?php echo $f['price']; ?>">
              <button type="submit" name="add_to_cart" class="btn btn-warning btn-sm rounded-pill">Add to Cart</button>
            </form>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include('includes/footer.php'); ?>
