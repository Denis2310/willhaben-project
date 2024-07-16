<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products Page</title>
<style>
  /* Body style */
  body {
    font-family: "Almarai", sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
  }

  /* Container styles */
  .container-home {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
  }

  /* Card styles */
  .card {
    border: 1px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
    margin: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    flex: 1 1 calc(33.333% - 20px);
    display: flex;
    flex-direction: column;
  }

  .card-header, .card-footer {
    background-color: #f8f8f8;
    padding: 0.8rem;
    border-bottom: 1px solid #ccc;
  }

  .card-header {
    font-size: 1.2rem;
    font-weight: bold;
  }

  .card-footer {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-img-top {
    width: 100%;
    height: auto;
    max-height: 150px; /* Set a max height for the image */
    object-fit: cover;
  }

  .card-body {
    padding: 0.8rem;
    flex-grow: 1;
  }

  .card-title {
    font-size: 1rem;
    margin-bottom: 0.3rem;
  }

  .card-text {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0.5rem;
  }

  .card-price {
    font-size: 0.9rem;
    color: #333;
    font-weight: bold;
  }

  .btn {
    background-color: #1a73e8;
    color: #fff;
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    text-align: center;
  }

  .btn:hover {
    background-color: #0b59b6;
  }

  /* Heading style */
  h2 {
    font-size: 1.5rem;
    color: #333;
    text-align: center;
    margin-bottom: 1rem;
  }

  /* No products message */
  .no-products {
    font-size: 1rem;
    color: #666;
    text-align: center;
  }

  /* Flex container for cards */
  .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .card {
      flex: 1 1 calc(50% - 20px);
    }
  }

  @media (max-width: 480px) {
    .card {
      flex: 1 1 100%;
    }
  }
</style>
</head>
<body>

<?php
session_start();
if ($_SESSION['role'] == 'admin') {
  require 'layout/template/headerAdmin.php';
}else {
  require 'layout/template/header.php';
}

require 'Class/Products.php';

// Fetch products
$product = new Products();
$products = $product->read();
?>

<div class="container-home">
  <h2>Products Available for Purchase</h2>

  <div class="row">

  <?php if (!empty($products)) : ?>
    <?php foreach ($products as $product) : ?>
      <div class="card">
        <div class="card-header">
          <?= htmlspecialchars($product['name']); ?>
        </div>
        <img src="/layout/admin/products/image/<?= htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
        <div class="card-body">
          <a href="Product/index.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
          <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
          <p class="card-price">Price: $<?= number_format($product['price'], 2); ?></p>
        </div>
        <div class="card-footer">
          <!--If coustomer not Logged in -->
          <?php if($_SESSION['loggedin']!== true ) {?><a href="login/index.php" class="btn">Buy Now</a><?php }else { ?>
          <a href="cart/addToCart.php?id=<?= $product['id'];?>" class="btn">Buy Now</a>
          <?php } ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <p class="no-products">No products available for purchase.</p>
  <?php endif; ?>
  </div>
</div>
<?php require 'layout/template/footer.php'; ?>

