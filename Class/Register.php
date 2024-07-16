<?php
require_once 'Database.php';

/**
 * Class Register
 * Handles user registration functionality.
 */
class Register {
    /** @var mysqli $conn The mysqli database connection object. */
    private $conn;
    /** @var string $message The message to be displayed, usually for errors or success notifications. */
    private $message;

    /**
     * Register constructor.
     * Initializes the Database connection.
     */
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->message = '';
    }

    /**
     * Registers a new user.
     *
     * @param string $email The email address of the user.
     * @param string $password The password of the user.
     * @param string $firstname The first name of the user.
     * @param string $lastname The last name of the user.
     */
    public function registerUser($email, $password, $firstname, $lastname) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = htmlspecialchars(trim($email));
            $password = htmlspecialchars(trim($password));
            $firstname = htmlspecialchars(trim($firstname));
            $lastname = htmlspecialchars(trim($lastname));
            $role = 'customer'; // Default role for new users
    
            // Validate input
            if (empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
                $this->message = "All fields are required.";
            } else {
                // Check if email is valid
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->message = "Invalid email format.";
                } else {
                    // Check if email already exists
                    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
    
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $this->message = "Email already exists. Please choose another.";
                    } else {
                        // Validate password using regex
                        $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
                        if (!preg_match($password_pattern, $password)) {
                            $this->message = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special symbol.";
                        } else {
                            // Hash password
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            // Insert user into database
                            $stmt = $this->conn->prepare("INSERT INTO users (email, password, firstname, lastname, role) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param('sssss', $email, $hashedPassword, $firstname, $lastname, $role);
    
                            if ($stmt->execute()) {
                                //$this->message = "Registration successful!";
                                header('Location: ../index.php');
                            } else {
                                $this->message = "Registration failed. Please try again.";
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Gets the message related to the registration process.
     *
     * @return string The message.
     */
    public function getMessage() {
        return $this->message;
    }
}
?>
