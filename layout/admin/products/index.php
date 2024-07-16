<?php
session_start();

if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    require_once('../../template/headerAdmin.php'); 
    require_once('../../../Class/Products.php'); 

    $product = new Products();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $description = htmlspecialchars(trim($_POST['description']));
        $price = (float)$_POST['price'];
        $image = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = basename($_FILES['image']['name']);
            $target = "image/" . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }

        $product->create($name, $description, $price, $image);
        header('Location: index.php');
    }
?>
    <div class="container">
        <h2>Manage Products</h2>
        <form method="POST" action="productCreate.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" name="create" class="btn">Create Product</button>
        </form>
        <hr>
        <h3>Existing Products</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP code to display products -->
                <?php
                $result = $product->read();
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    $description =  implode(' ', array_slice(str_word_count($row['description'], 2), 0, 5));
                    echo "<td>{$description}</td>";
                    echo "<td>\${$row['price']}</td>";
                    echo "<td><img src='image/{$row['image']}' alt='{$row['name']}' width='50'></td>";
                    echo "<td>
                            <a style='text-decoration: none; color: #1a73e8;' href='../../../Product/index.php?id={$row['id']}'>View</a> |
                            <a style='text-decoration: none; color: #1a73e8;' href='productEdit.php?id={$row['id']}'>Edit</a> |
                            <a style='text-decoration: none; color: #1a73e8;' href='productDelete.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this record?')\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<?php 
    require_once('../../template/footer.php'); 
} else {
    // If not admin, redirect to index.php or another appropriate page
    header('Location: ../../../index.php');
    exit;
}
?>
