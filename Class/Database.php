<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'lap' with password lap) 
https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
*/

class Database {
    // Define database connection parameters
    private $host = "localhost";
    private $dbName = "lap";
    private $username = "lap";
    private $password = "lap";
    public $conn;

    // Method to get the database connection
    public function getConnection() {
        $this->conn = null;

        // Attempt to establish a database connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbName);

        // Check if the connection was successful
        if ($this->conn->connect_error) {
            // Display an error message if the connection fails
            echo "Connection error: " . $this->conn->connect_error;
            $this->conn = null;
        }

        // Return the connection object (null if failed)
        return $this->conn;
    }
}
?>
