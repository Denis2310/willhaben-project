<?php

// Check if user is logged in and has appropriate permissions
if ($_SESSION['role'] == 'admin') {
    // Redirect to appropriate page or handle unauthorized access
    require '../layout/template/headerAdmin.php';
}
require '../layout/template/header.php';


require '../Class/Cart.php';
$userId = $_SESSION['id'];
$productId = (int)$_GET['id'];
$quantity = 1;
$createdAt = new DateTime(); // Current date and time

// Instance from Cart
$addToCart = new Cart();
$addToCart->addToCart($userId, $productId, $quantity, $createdAt);

header('Location: index.php');
exit;
?>
