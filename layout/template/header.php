<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Include Font Awesome for the menu icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/asset/css/style.css">
    <!-- google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
</head>

<body>
<?php session_start(); ?>
    <div class="page-header">
        <div class="logo">
            <p>Webshop</p>
        </div>
        <a id="menu-icon" class="menu-icon" onclick="onMenuClick()">
            <i class="fas fa-bars"></i>
        </a>
        <div id="navigation-bar" class="nav-bar">
            <a href="http://localhost:8451/" class="active">Home</a>
            <a href="http://localhost:8451/order/index.php">Order</a>
            <a href="http://localhost:8451/cart/index.php">Cart</a>
            <?php 
            
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
              echo '<a href="#">Welcome, '; echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); echo '</a>';  
              echo '<a href="../../logout/">Logout</a>';
            }else{
              echo'<a href="http://localhost:8451/login">Signin</a>';
            }    
            ?>
        </div>
    </div>
    <script>
        function onMenuClick() {
            var navbar = document.getElementById("navigation-bar");
            var responsive_class_name = "responsive";

            navbar.classList.toggle(responsive_class_name);
        }
    </script>

