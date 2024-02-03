<?php
class User {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function register($username, $password, $fullname) {
        // Perform registration logic here, e.g., insert user into the database

        // Get the maximum PostID from the table
        $conn = $this->db->getConnection();
        $maxIdQuery = "SELECT MAX(UserID) AS maxId FROM users";
        $result = $conn->query($maxIdQuery);
        
        if ($result && $row = $result->fetch_assoc()) {
            $postId = $row['maxId'] + 1;
        } else {
            // If there are no existing posts, start with PostID 1
            $postId = 1;
        }

        $conn = $this->db->getConnection();
        // You should perform proper input validation and password hashing here
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Use prepared statement to insert user into the database
        $query = "INSERT INTO users (UserID ,username, password, Fullname) VALUES (?,?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $postId, $username, $hashedPassword, $fullname);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $password) {
        // Perform login logic here, e.g., check credentials against the database
        $conn = $this->db->getConnection();

        // Use prepared statement for login
        $query = "SELECT * FROM users WHERE Username=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
                // Start a session and store user information
                session_start();
                $_SESSION['user_id'] = $user['UserID']; // Adjust this based on your user table structure
                $_SESSION['username'] = $user['Username'];
                $_SESSION['fullname'] = $user['Fullname'];
                
                // Regenerate session ID for security
                session_regenerate_id(true);

                return true;
            }
        }

        return false;
    }

    public function update($userId, $newUsername, $newPassword) {
        // Perform update logic here, e.g., update user information in the database
        $conn = $this->db->getConnection();
        // You should perform proper input validation and password hashing here
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        // Use prepared statement to update user in the database
        $query = "UPDATE users SET username=?, password=? WHERE UserID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $newUsername, $hashedPassword, $userId);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser($userId) {
        // Retrieve user information based on user ID
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM users WHERE UserID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function updateUser($userId, $newUsername, $newPassword) {
        // Update user information in the database
        $conn = $this->db->getConnection();
        // You should perform proper input validation and password hashing here
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        // Use prepared statement to update user in the database
        $query = "UPDATE users SET username=?, password=? WHERE UserID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $newUsername, $hashedPassword, $userId);
    
        return $stmt->execute();
    }

    
}
?>