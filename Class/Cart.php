<?php

require 'Database.php'; // Assuming Database.php contains the Database class for connection

/**
 * Class Cart
 * Manages the shopping cart operations including fetching, updating, adding, and removing cart items.
 */
class Cart {
    /** @var mysqli $conn The mysqli database connection object. */
    private $conn;

    /**
     * Cart constructor.
     * Initializes the Database connection.
     */
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Fetches the cart items for a specific user.
     *
     * @param int $userId The ID of the user.
     * @return array An associative array of the user's cart items.
     */
    public function getCartItems($userId) {
        $query = "SELECT cart.id, products.name, products.price, products.image, cart.quantity
                  FROM cart
                  INNER JOIN products ON cart.product_id = products.id
                  WHERE cart.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
        $stmt->close();
        return $cartItems;
    }

    /**
     * Updates the quantity of a cart item.
     *
     * @param int $cartId The ID of the cart item.
     * @param int $newQuantity The new quantity to set for the cart item.
     * @return bool True on success, False on failure.
     */
    public function updateCartItemQuantity($cartId, $newQuantity) {
        $query = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $newQuantity, $cartId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Adds an item to the cart.
     *
     * @param int $userId The ID of the user.
     * @param int $productId The ID of the product.
     * @param int $quantity The quantity of the product.
     * @param DateTime $createdAt The datetime when the item is added to the cart.
     * @return bool True on success, False on failure.
     */
    public function addToCart($userId, $productId, $quantity, $createdAt) {
        // Format the DateTime object as a string in 'yyyy-mm-dd HH:ii:ss' format
        $created_at = $createdAt->format('Y-m-d H:i:s');
        
        $query = "INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiis", $userId, $productId, $quantity, $created_at);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Removes an item from the cart.
     *
     * @param int $cartId The ID of the cart item to remove.
     * @return bool True if the item was removed successfully, False otherwise.
     */
    public function removeFromCart($cartId) {
        $query = "DELETE FROM cart WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cartId);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows > 0;
        $stmt->close();
        return $affectedRows;
    }
}
?>
