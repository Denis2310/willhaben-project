<?php
session_start();

if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    require_once '../../../layout/template/headerAdmin.php'; // Adjust path as per your file structure
    require_once '../../../Class/Statistics.php'; // Adjust path as per your file structure

    $stats = new Statistics();

    // Fetch the least ordered products
    $leastOrderedProducts = $stats->getLeastOrderedProducts();

    // Fetch order history over the last four weeks
    $orderHistory = $stats->getOrderHistoryLastFourWeeks();

    // Fetch the most ordered products
    $mostOrderedProducts = $stats->getMostOrderedProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

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

        .stats-section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
        }

        .stats-section h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
            color: #333;
        }

        .stats-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .stats-list li {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Statistics Dashboard</h2>

        <!-- Section for the 5 Least Ordered Products -->
        <div class="stats-section">
            <h3>The 5 Least Ordered Products</h3>
            <ul class="stats-list">
                <?php foreach ($leastOrderedProducts as $product): ?>
                    <li><?php echo htmlspecialchars($product['name']); ?> - Ordered <?php echo $product['order_count']; ?> times</li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Section for Order History Last 4 Weeks -->
        <div class="stats-section">
            <h3>Order History Last 4 Weeks</h3>
            <ul class="stats-list">
                <?php foreach ($orderHistory as $history): ?>
                    <li><?php echo htmlspecialchars($product['name']); ?> - <?php echo htmlspecialchars($history['order_date']); ?> - <?php echo $history['order_count']; ?> orders</li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Section for the Top 5 Most Ordered Products -->
        <div class="stats-section">
            <h3>Top 5 Most Ordered Products</h3>
            <ul class="stats-list">
                <?php foreach ($mostOrderedProducts as $product): ?>
                    <li><?php echo htmlspecialchars($product['name']); ?> - Ordered <?php echo $product['order_count']; ?> times</li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
<?php require'../../template/footer.php'; } else {
    header('Location: ../../../index.php');
    exit;
}
?>

