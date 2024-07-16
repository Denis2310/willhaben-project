<?php
require('../layout/template/header.php');
require('../Class/Login.php');
$login = new Login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login->loginUser($email, $password);
    $message = $login->getMessage();
}
?>
    <div class="container-register">
        <h2>User Login</h2>
        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <p>I dont have an account <a href="http://localhost:8451/register/">register</a> </p>
            <button type="submit" class="btn">Login</button>
        </form>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
  <?php require '../layout/template/footer.php';  ?>