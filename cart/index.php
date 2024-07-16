<?php
require '../Class/Cart.php';
require '../layout/template/header.php';

$userId = $_SESSION['id']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Include your CSS styles here -->
    <style>
        /* Define your CSS styles for cart items */
        .cart-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .cart-item {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-item .product-name {
            font-weight: bold;
        }
        
        .cart-item .product-price {
            margin-left: 10px;
        }
        
        .cart-item .product-quantity {
            margin-left: 10px;
        }
        
        .remove-item {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .remove-item:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h2>Shopping Cart</h2>
        <div class="cart-items">
            <?php
                $cart = new Cart();
                $cartItems = $cart->getCartItems($userId);
                
                if ($cartItems) {
                    foreach ($cartItems as $item) {?>
                        <div class="cart-item">
                            <img style="width: 5%;" src="../layout/admin/products/image/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="product-price">$<?php echo number_format($item['price'], 2); ?></span>
                            <form action="updateCart.php?id=<?php echo htmlspecialchars($item['id']); ?>" method="post">
                                <input type="number" min="1" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>">
                                <button type="submit" class="update-item" data-cart-id="<?php echo htmlspecialchars($item['id']); ?>">Update</button>
                            </form>
                            <span class="total-price">$<?php echo $result = number_format($item['price'], 2) * htmlspecialchars($item['quantity']); ?></span>
                            <a href="deleteCart.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="remove-item" data-cart-id="<?php echo htmlspecialchars($item['id']); ?>">Remove</a>
                            <a href="checkout.php?id=<?=$item['id'];?>" class="remove-item" data-cart-id="<?php echo htmlspecialchars($item['id']); ?>">Checkout</a>
                        </div>
                   <?php }
                } else {
                  echo '<p>No items in the cart. Return to Home Shop <a href="../index.php" class="remove-item">Home-Shop</a></p>';
                }
            ?>
        </div>
    </div>
</body>
</html>
