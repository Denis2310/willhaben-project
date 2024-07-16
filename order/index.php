<?php
require '../layout/template/header.php'; // Adjust path as per your file structure
require '../Class/Order.php';

session_start(); // Ensure the session is started
$userId = $_SESSION['id']; // Fetch user ID from session

$orderObj = new Order();
$orders = $orderObj->getOrdersByUser($userId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .order {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .order-details {
            margin-bottom: 10px;
        }
        .order-items {
            margin-bottom: 10px;
            padding-left: 20px;
        }
        .order-items ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .order-items ul li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        .order-items ul li img {
            width: 50px;
            height: auto;
            margin-right: 10px;
            border-radius: 4px; /* Optional: Add border-radius for rounded corners */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Optional: Add box shadow for image */
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 10px;
            }
            .order {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order History</h2>
        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <div class="order-header">
                        <div>
                            <strong>Order ID:</strong> <?php echo $order['id']; ?>
                        </div>
                        <div>
                            <strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?>
                        </div>
                        <div>
                            <strong>Order Date:</strong> <?php echo date('M d, Y H:i:s', strtotime($order['created_at'])); ?>
                        </div>
                    </div>
                    <div class="order-details">
                        <strong>Order Details:</strong>
                        <div class="order-items">
                            <ul>
                                <?php foreach ($order['items'] as $item): ?>
                                    <li>
                                        <img src="../layout/admin/products/image/<?php echo $item['image']; ?>" alt="<?php echo $item['product_name']; ?>">
                                        <?php echo $item['product_name']; ?> - Quantity: <?php echo $item['quantity']; ?> - Unit Price: $<?php echo number_format($item['unit_price'], 2); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php require'../layout/template/footer.php';?>
