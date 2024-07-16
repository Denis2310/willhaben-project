<?php
require '../layout/template/header.php';
require '../Class/Database.php'; 
session_start(); // Ensure the session is started
$userId = $_SESSION['id']; // Fetch user ID from session
$cartId = $_GET['id'];

// Database connection
$conn = new Database();
$conn = $conn->getConnection();

// Function to generate invoice number
function generateInvoiceNumber($orderId) {
    return 'INV' . str_pad($orderId, 8, '0', STR_PAD_LEFT);
}

// Function to send invoice email
function sendInvoiceEmail($email, $invoiceNumber, $orderDetails) {
    $subject = "Your Invoice - $invoiceNumber";
    $message = "Dear Customer,\n\nThank you for your order. Here are your order details:\n\n";
    $message .= $orderDetails;
    $headers = 'From: no-reply@example.com' . "\r\n" .
               'Reply-To: no-reply@example.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    mail($email, $subject, $message, $headers);
}

// Fetch cart items and calculate total amount
$stmt = $conn->prepare("
    SELECT c.product_id, c.quantity, p.price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.id = ? AND c.user_id = ?
");
$stmt->bind_param("ii", $cartId, $userId);
$stmt->execute();
$result = $stmt->get_result();

$totalAmount = 0;
$products = [];

while ($row = $result->fetch_assoc()) {
    $productId = $row['product_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $products[] = [
        'product_id' => $productId,
        'quantity' => $quantity,
        'unit_price' => $price
    ];
    $totalAmount += $quantity * $price;
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process address information
    $addressLine1 = htmlspecialchars($_POST['address_line1']);
    $addressLine2 = htmlspecialchars($_POST['address_line2']);
    $city = htmlspecialchars($_POST['city']);
    $postalCode = htmlspecialchars($_POST['postal_code']);
    $country = htmlspecialchars($_POST['country']);

    // Validate address inputs
    $errors = [];
    if (empty($addressLine1)) {
        $errors[] = "Address Line 1 is required.";
    }
    if (empty($city)) {
        $errors[] = "City is required.";
    }
    if (empty($postalCode)) {
        $errors[] = "Postal Code is required.";
    }
    if (empty($country)) {
        $errors[] = "Country is required.";
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        // Start database transaction
        $conn->begin_transaction();

        try {
            // Insert into orders table
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
            $stmt->bind_param("id", $userId, $totalAmount);
            $stmt->execute();
            $orderId = $stmt->insert_id;
            $stmt->close();

            // Insert into addresses table
            $stmt = $conn->prepare("INSERT INTO addresses (user_id, type, address_line1, address_line2, city, postal_code, country) VALUES (?, 'shipping', ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $userId, $addressLine1, $addressLine2, $city, $postalCode, $country);
            $stmt->execute();
            $addressId = $stmt->insert_id;
            $stmt->close();

            // Insert into order_items table
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, address_id) VALUES (?, ?, ?, ?, ?)");
            foreach ($products as $product) {
                $productId = $product['product_id'];
                $quantity = $product['quantity'];
                $unitPrice = $product['unit_price'];
                $stmt->bind_param("iiidi", $orderId, $productId, $quantity, $unitPrice, $addressId);
                $stmt->execute();
            }
            $stmt->close();

            // Delete items from cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $cartId, $userId);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();

            // Generate invoice number
            $invoiceNumber = generateInvoiceNumber($orderId);

            // Send invoice email
            $orderDetails = "Invoice Number: $invoiceNumber\nOrder ID: $orderId\nTotal Amount: $totalAmount\n";
            // Additional order details can be added here
            sendInvoiceEmail($_POST['email'], $invoiceNumber, $orderDetails);
            
            // Display success message
            echo '<div style="text-align: center; margin-top: 20px; background-color: #d4edda; color: #155724; border-color: #c3e6cb; border-radius: .25rem; padding: 10px;">';
            echo "Order successfully placed! Your invoice number is: $invoiceNumber";
            echo '</div>';
            // Redirect to homepage after 2 seconds
            echo '<meta http-equiv="refresh" content="2;url=../index.php">';
            // Redirect to order confirmation page if needed
            // header('Location: ../order/index.php');
        } catch (Exception $e) {
            // Rollback transaction on failure
            $conn->rollback();
            echo "Failed to place order: " . $e->getMessage();
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo '<div style="color: red;">' . $error . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        .container-Checkout {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #343a40;
        }
        form h3 {
            margin-bottom: 10px;
            color: #495057;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }
        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 15px;
            }
            input[type="text"],
            input[type="submit"] {
                padding: 8px;
                margin-bottom: 10px;
            }
        }
        @media (max-width: 400px) {
            h2 {
                font-size: 24px;
            }
            form h3 {
                font-size: 18px;
            }
            label {
                font-size: 14px;
            }
            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container-Checkout">
        <h2>Checkout</h2>
        <form method="POST" action="checkout.php?id=<?php echo $cartId; ?>">
            <div class="form-group">
                <h3>Address Information</h3>
                <label for="address_line1">Address Line 1:</label>
                <input type="text" id="address_line1" name="address_line1" required>
            </div>
            
            <div class="form-group">
                <label for="address_line2">Address Line 2:</label>
                <input type="text" id="address_line2" name="address_line2">
            </div>
            
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </div>
            
            <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" required>
            </div>
            
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required>
            </div>
            
            <div class="form-group">
                <h3>Payment Information</h3>
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>
            
            <div class="form-group">
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" required>
            </div>
            
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>
                        
            <input type="submit" value="Place Order">
        </form>
    </div>

<?php require '../layout/template/footer.php'; ?>
