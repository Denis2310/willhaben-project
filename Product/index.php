<?php
session_start();
// Check user role and include appropriate header
if ($_SESSION['role'] == 'admin') {
    require('../layout/template/headerAdmin.php');
} else {
    require('../layout/template/header.php');
}
require('../Class/Products.php');

$product = new Products();
$productData = null;

// Fetch product data if ID is provided in URL
if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    $productData = $product->view($productId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product</title>
    <style>
        /* Container and content styles */
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            display: flex;
            align-items: flex-start;
            border-bottom: 1px solid #ccc;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .product-details img {
            max-width: 200px;
            height: auto;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
            margin-left: 20px;
        }

        .product-info h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        .product-info p {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .product-info .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1a73e8;
            margin-top: 10px;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1a73e8;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-button a:hover {
            background-color: #0b59b6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Product Details</h2>
        <?php if ($productData) : ?>
            <div class="product-details">
                <img src="../layout/admin/products/image/<?php echo htmlspecialchars($productData['image']); ?>" alt="<?php echo htmlspecialchars($productData['name']); ?>">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($productData['name']); ?></h3>
                    <p><?php echo htmlspecialchars($productData['description']); ?></p>
                    <p class="price">Price: $<?php echo number_format($productData['price'], 2); ?></p>
                </div>
            </div>
        <?php else : ?>
            <p>No product found.</p>
        <?php endif; ?>

        <div class="back-button">
            <a href="javascript:history.back()" class="btn">Back</a>
        </div>
    </div>
</body>
</html>
