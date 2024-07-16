<?php
require_once '../Class/Register.php';
require('../layout/template/header.php');
$register = new Register();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $register->registerUser($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname']);
}

$message = $register->getMessage();
?>
    <div class="container-register">
        <h2>User Registration</h2>
        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Repeat Password</label>
                <input type="password" name="repeat_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php if(isset($_POST['firstname'])) echo htmlspecialchars($_POST['firstname']); ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" value="<?php if(isset($_POST['lastname'])) echo htmlspecialchars($_POST['lastname']); ?>" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

<?php require '../layout/template/footer.php';  ?>