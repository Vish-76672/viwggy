<?php
// restaurant.php

// ✅ Start output buffering and session at the very top
ob_start();
session_start();

// ✅ Include database connection (safe: no HTML here)
include 'includes/db.php';

// ✅ Handle Add to Cart request before any HTML is sent
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If item already in cart, increase quantity
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Add new item
        $_SESSION['cart'][$item_id] = [
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => $quantity
        ];
    }

    // ✅ Redirect BEFORE including header.php (prevents header warnings)
    header("Location: cart.php");
    exit();
}

// ✅ Now safe to include header.php (which prints HTML)
include 'includes/header.php';
?>

<!-- ✅ Restaurant Page HTML -->
<div class="container my-5">
    <h1 class="text-center mb-4 text-warning">🍔 Viwggy Restaurants</h1>
    <p class="text-center text-muted">Order your favorite meals instantly</p>

    <div class="row">
        <!-- Example restaurant 1 -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="assets/img/food1.jpg" class="card-img-top" alt="Burger">
                <div class="card-body">
                    <h5 class="card-title">Cheese Burst Burger</h5>
                    <p class="card-text">₹120</p>
                    <form method="POST" action="restaurant.php">
                        <input type="hidden" name="item_id" value="1">
                        <input type="hidden" name="item_name" value="Cheese Burst Burger">
                        <input type="hidden" name="item_price" value="120">
                        <button type="submit" name="add_to_cart" class="btn btn-warning w-100">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Example restaurant 2 -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="assets/img/food2.jpg" class="card-img-top" alt="Pizza">
                <div class="card-body">
                    <h5 class="card-title">Pepperoni Pizza</h5>
                    <p class="card-text">₹250</p>
                    <form method="POST" action="restaurant.php">
                        <input type="hidden" name="item_id" value="2">
                        <input type="hidden" name="item_name" value="Pepperoni Pizza">
                        <input type="hidden" name="item_price" value="250">
                        <button type="submit" name="add_to_cart" class="btn btn-warning w-100">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Example restaurant 3 -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="assets/img/food3.jpg" class="card-img-top" alt="Pasta">
                <div class="card-body">
                    <h5 class="card-title">Creamy Alfredo Pasta</h5>
                    <p class="card-text">₹180</p>
                    <form method="POST" action="restaurant.php">
                        <input type="hidden" name="item_id" value="3">
                        <input type="hidden" name="item_name" value="Creamy Alfredo Pasta">
                        <input type="hidden" name="item_price" value="180">
                        <button type="submit" name="add_to_cart" class="btn btn-warning w-100">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
// ✅ End output buffering at the very bottom
ob_end_flush();
?>
