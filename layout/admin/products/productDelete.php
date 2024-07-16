<?php
require('../../../Class/Products.php');

if (isset($_GET['id'])) {
    $product = new Products();
    $id = (int)$_GET['id'];
    $product->delete($id);
    header('Location: index.php');
}
?>
