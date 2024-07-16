<?php

require_once 'Database.php'; // Adjust path as per your file structure

/**
 * Class Order
 * Handles operations related to orders such as fetching orders for a specific user.
 */
class Order
{
    /** @var mysqli $conn The mysqli database connection object. */
    private $conn;

    /**
     * Order constructor.
     * Initializes the Database connection.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Fetch all orders for a specific user.
     *
     * @param int $userId The ID of the user.
     * @return array An array of orders, each containing order details and associated items.
     */
    public function getOrdersByUser($userId)
    {
        $stmt = $this->conn->prepare("
            SELECT o.id, o.total_amount, oi.product_id, oi.quantity, oi.unit_price, p.name AS product_name, p.image
            FROM orders o
            INNER JOIN order_items oi ON o.id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ?
            ORDER BY o.id DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['id'];
            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'id' => $orderId,
                    'total_amount' => $row['total_amount'],
                    'items' => []
                ];
            }
            $orders[$orderId]['items'][] = [
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price']
            ];
        }

        $stmt->close();
        return array_values($orders); // Convert associative array to indexed array
    }
}
?>
