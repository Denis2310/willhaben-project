<?php
require 'Database.php';

/**
 * Class Products
 * Handles CRUD operations for products in the database.
 */
class Products {
    /** @var mysqli $conn The mysqli database connection object. */
    private $conn;

    /**
     * Products constructor.
     * Initializes the Database connection.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Create a new product.
     *
     * @param string $name The name of the product.
     * @param string $description The description of the product.
     * @param float $price The price of the product.
     * @param string $image The image path or URL of the product.
     * @return bool True on success, false on failure.
     */
    public function create($name, $description, $price, $image) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssds', $name, $description, $price, $image);
        return $stmt->execute();
    }

    /**
     * Read all products.
     *
     * @return mysqli_result|false The result set of products on success, false on failure.
     */
    public function read() {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * View a specific product by ID.
     *
     * @param int $id The ID of the product.
     * @return array|null The product data as an associative array, or null if not found or on error.
     */
    public function view($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null; // No product found with the given ID
            }
        } else {
            return null; // Error executing the query
        }
    }

    /**
     * Update an existing product.
     *
     * @param int $id The ID of the product.
     * @param string $name The name of the product.
     * @param string $description The description of the product.
     * @param float $price The price of the product.
     * @param string $image The image path or URL of the product.
     * @return bool True on success, false on failure.
     */
    public function update($id, $name, $description, $price, $image) {
        $stmt = $this->conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param('ssdsi', $name, $description, $price, $image, $id);
        return $stmt->execute();
    }

    /**
     * Delete a product by ID.
     *
     * @param int $id The ID of the product.
     * @return bool True on success, false on failure.
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Get a product by ID.
     *
     * @param int $id The ID of the product.
     * @return array|null The product data as an associative array, or null if not found or on error.
     */
    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null; // No product found with the given ID
            }
        } else {
            return null; // Error executing the query
        }
    }
}
?>
