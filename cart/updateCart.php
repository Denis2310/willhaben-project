<?php
session_start();
require '../Class/Cart.php';

if ($_SESSION['role'] == 'admin') {
    require '../layout/template/headerAdmin.php';
} else {
    require '../layout/template/header.php';
}

if (isset($_GET['id']) && isset($_POST['quantity'])) {
    $cart_id = $_GET['id'];
    $new_quantity = $_POST['quantity'];
    
    $cart = new Cart();
    $success = $cart->updateCartItemQuantity($cart_id, $new_quantity);
    
    if ($success) {
        header('Location: index.php'); // Redirect to the shopping cart page or any other page as needed
        exit;
    } else {
        echo "Failed to update quantity.";
    }
} else {
    echo "Invalid request.";
}
?>
