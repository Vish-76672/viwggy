<?php
// index.php
include('includes/header.php');
?>
<section class="hero text-white text-center">
  <div class="container">
    <h1 class="display-5 fw-bold">Welcome to <span class="text-warning">Viwggy</span></h1>
    <p class="lead mb-4">Order your favourite meals from local restaurants — fast delivery, great taste.</p>

    <form class="d-flex justify-content-center mb-4" method="GET" action="restaurant.php" role="search">
      <input class="form-control w-50 rounded-pill px-4" name="q" placeholder="Search restaurants or dishes...">
      <button class="btn btn-warning ms-2 rounded-pill">Search</button>
    </form>
  </div>
</section>

<section class="restaurants py-5">
  <div class="container">
    <h2 class="mb-4 fw-bold">Popular Restaurants</h2>
    <div class="row">
      <?php
      // sample restaurant data (replace / extend with DB later)
      $restaurants = [
        ['id'=>1,'name'=>'Burger Hub','image'=>'assets/img/restaurant1.jpg','rating'=>'4.5'],
        ['id'=>2,'name'=>'Pizza Planet','image'=>'assets/img/restaurant2.jpg','rating'=>'4.2'],
        ['id'=>3,'name'=>'Healthy Bowl','image'=>'assets/img/restaurant3.jpg','rating'=>'4.7'],
      ];
      foreach($restaurants as $r): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
          <img src="<?php echo $r['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($r['name']); ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($r['name']); ?></h5>
            <p class="text-muted mb-2">Rating: <?php echo $r['rating']; ?> ⭐</p>
            <a href="restaurant.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-warning rounded-pill">View Menu</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
