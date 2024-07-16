<?php
session_start();
require '../Class/Cart.php';

if ($_SESSION['role'] == 'admin') {
    require '../layout/template/headerAdmin.php';
} else {
    require '../layout/template/header.php';
}

if (isset($_GET['id'])) {
    $cart_id = $_GET['id'];
    $cart = new Cart();
    $cart->removeFromCart($cart_id); 
    header('Location: ../index.php');
    exit;
} else {
    echo "No item ID provided for deletion.";
}
?>
