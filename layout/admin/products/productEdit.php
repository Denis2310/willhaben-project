<?php 
require('../../template/headerAdmin.php');
require('../../../Class/Products.php');

$product = new Products();

if (isset($_GET['id'])) {
    $productData = $product->getProductById((int)$_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = (float)$_POST['price'];
    $image = $productData['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        $target = "image/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $product->update($id, $name, $description, $price, $image);
    header('Location: admin_panel.php');
}

?>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" action="productEdit.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $productData['id'] ?>">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?= $productData['name'] ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?= $productData['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" value="<?= $productData['price'] ?>" required>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                <img src="image/<?= $productData['image'] ?>" alt="<?= $productData['name'] ?>" width="100">
            </div>
            <button type="submit" name="update" class="btn">Update Product</button>
            <!-- Button with onclick event to navigate back -->
            <button class="btn"><a href="index.php" style="text-decoration: none; color: aliceblue;">Back</a></button>
        </form>
    </div>

<?php require '../../template/footer.php'; ?>
