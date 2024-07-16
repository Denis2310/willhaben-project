<?php
// Quelle https://docs.devsense.com/en/vs/editor/phpdoc
require_once 'Database.php';

/**
 * Class Login
 * Handles user login functionality including validation, authentication,
 * and session management.
 */
class Login {
    /** @var Database $db The Database object used for database operations. */
    private $db;
    
    /** @var mysqli $conn The mysqli database connection object. */
    private $conn;
    
    /** @var string $message Holds error or success messages related to login attempts. */
    private $message;

    /**
     * Login constructor.
     * Initializes the Database connection and sets initial values.
     */
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
        $this->message = '';
    }

    /**
     * Attempt to log in a user with provided credentials.
     *
     * @param string $email The user's email address.
     * @param string $password The user's password.
     */
    public function loginUser($email, $password) {
        if ($this->conn) {
            $email = htmlspecialchars(trim($email));
            $password = htmlspecialchars(trim($password));

            if (empty($email) || empty($password)) {
                $this->message = "All fields are required.";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->message = "Invalid email format.";
                return;
            }

            // Prepare our SQL statement to prevent SQL injection.
            $stmt = $this->conn->prepare("SELECT id, password, firstname, lastname, role FROM users WHERE email = ?");
            
            if ($stmt) {
                // Bind parameters (s = string), in our case the email is a string so we use "s".
                $stmt->bind_param('s', $email);
                $stmt->execute();

                // Store the result so we can check if the account exists in the database.
                $stmt->store_result();

                if ($stmt->num_rows === 1) {
                    // Bind result variables and fetch values.
                    $stmt->bind_result($id, $hashed_password, $firstname, $lastname, $role);
                    $stmt->fetch();

                    // Verify password using PHP's password_verify function.
                    if (password_verify($password, $hashed_password)) {
                        // Start the session and set session variables upon successful login.
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['email'] = $email;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['role'] = $role;
                        
                        // Redirect to home page after successful login.
                        header('Location: ../index.php');
                    } else {
                        $this->message = "Invalid password.";
                    }
                } else {
                    $this->message = "No account found with that email.";
                }
                $stmt->close();
            } else {
                $this->message = "Database query error.";
            }
        } else {
            $this->message = "Database connection error.";
        }
    }

    /**
     * Retrieve the current login message.
     *
     * @return string The message related to the most recent login attempt.
     */
    public function getMessage() {
        return $this->message;
    }
}
?>
