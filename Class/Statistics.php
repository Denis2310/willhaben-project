<?php

require_once 'Database.php';

/**
 * Class Statistics
 * Handles the retrieval of various statistical data related to products and orders.
 */
class Statistics
{
    /** @var mysqli $conn The mysqli database connection object. */
    private $conn;

    /**
     * Statistics constructor.
     * Initializes the Database connection.
     */
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Retrieves the five least ordered products.
     *
     * @return array An associative array of the least ordered products and their order counts.
     */
    public function getLeastOrderedProducts()
    {
        $stmt = $this->conn->prepare("
            SELECT p.id, p.name, COUNT(oi.product_id) AS order_count
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            GROUP BY p.id, p.name
            ORDER BY order_count ASC
            LIMIT 5
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products;
    }

    /**
     * Retrieves the order history over the last four weeks.
     *
     * @return array An associative array of the order history, grouped by date.
     */
    public function getOrderHistoryLastFourWeeks()
    {
        $stmt = $this->conn->prepare("
          SELECT DATE_FORMAT(o.order_date, '%Y-%m-%d') AS order_date, COUNT(*) AS order_count
          FROM orders o
          WHERE o.order_date >= DATE_SUB(NOW(), INTERVAL 4 WEEK)
          GROUP BY order_date
          ORDER BY order_date ASC
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $orderHistory = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $orderHistory;
    }

    /**
     * Retrieves the five most ordered products.
     *
     * @return array An associative array of the most ordered products and their order counts.
     */
    public function getMostOrderedProducts()
    {
        $stmt = $this->conn->prepare("
            SELECT p.id, p.name, COUNT(oi.product_id) AS order_count
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            GROUP BY p.id, p.name
            ORDER BY order_count DESC
            LIMIT 5
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products;
    }
}
?>
