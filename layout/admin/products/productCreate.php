<?php
require '../../../Class/Products.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = new Products();

    // Sanitize and trim input values
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = (float)$_POST['price'];
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        $target = "image/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    // Determine whether to create or update product based on button pressed
    if (isset($_POST['create'])) {
        // Create new product
        $product->create($name, $description, $price, $image);
    } elseif (isset($_POST['update'])) {
        // Update existing product
        $id = (int)$_POST['id'];
        $product->update($id, $name, $description, $price, $image);
    }

    // Redirect back to the product listing page after processing
    header('Location: index.php');
    exit; // Ensure script stops here to prevent further execution
}
?>
