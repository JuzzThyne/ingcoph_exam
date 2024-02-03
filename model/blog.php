<?php

class Post {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    private function sanitizeInput($input) {
        // Use appropriate sanitization methods based on your requirements
        return $this->db->getConnection()->real_escape_string(strip_tags($input));
    }

    public function getAllPosts() {
        // Retrieve all blog posts from the database
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM blogposts ORDER BY PostID DESC";
        $result = $conn->query($query);

        $posts = [];

        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }

    public function getMyPosts($userId) {
        // Retrieve all blog posts from the database
        $conn = $this->db->getConnection();
        
        $query = "SELECT * FROM blogposts WHERE UserID = ? ORDER BY PostID DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $userId);
    
        // Execute the statement
        $stmt->execute();
    
        // Get the result set
        $result = $stmt->get_result();
    
        $posts = [];
    
        // Fetch results into associative array
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    
        // Close the statement
        $stmt->close();
    
        return $posts;
    }
    

    public function createPost($title, $content, $userId, $image) {
        // Sanitize inputs
        $title = $this->sanitizeInput($title);
        $content = $this->sanitizeInput($content);
        $userId = $this->sanitizeInput($userId);
    
        // Upload the image to a directory on the server
        $imagePath = $this->uploadImage($image);
    
        // Check if image upload was successful
        if ($imagePath === false) {
            return false;
        }
    
        // Get the maximum PostID from the table
        $conn = $this->db->getConnection();
        $maxIdQuery = "SELECT MAX(PostID) AS maxId FROM blogposts";
        $result = $conn->query($maxIdQuery);
        
        if ($result && $row = $result->fetch_assoc()) {
            $postId = $row['maxId'] + 1;
        } else {
            // If there are no existing posts, start with PostID 1
            $postId = 1;
        }
    
        // Create a new blog post using prepared statement
        $query = "INSERT INTO blogposts (PostID, Title, Content, UserID, image_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issss", $postId, $title, $content, $userId, $imagePath);
    
        if ($stmt->execute()) {
            return $postId;
        } else {
            return false;
        }
    }
    

    private function uploadImage($image) {
        
        // Validate and move the uploaded image to a directory
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($image['name']);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'jfif'])) {
            return false; // Invalid file type
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            return $targetFile; // Return the path to the saved image
        } else {
            return false; // Error uploading file
        }
    }

    public function getPostById($postId) {
        // Retrieve a specific blog post by its ID
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM blogposts WHERE PostID = ? ORDER BY PostID DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        } else {
            return null; // Return null if post not found
        }
    }

    public function updatePost($postId, $title, $content, $image = null) {
        // Sanitize inputs
        $postId = $this->sanitizeInput($postId);
        $title = $this->sanitizeInput($title);
        $content = $this->sanitizeInput($content);
        $conn = $this->db->getConnection();

        // Check if a new image is provided
        if ($image !== null) {
            // Upload the new image to a directory on the server
            $newImagePath = $this->uploadImage($image);
    
            // Check if image upload was successful
            if ($newImagePath === false) {
                return false;
            }
    
            // Update the image path in the database
            $query = "UPDATE blogposts SET Title = ?, Content = ?, image_path = ? WHERE PostID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $title, $content, $newImagePath, $postId);
        } else {
            // No new image, update only title and content
            $query = "UPDATE blogposts SET Title = ?, Content = ? WHERE PostID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $title, $content, $postId);
        }
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function doesPostExist($postId) {
        // Sanitize input
        $postId = $this->sanitizeInput($postId);

        // Check if the post exists using prepared statement
        $conn = $this->db->getConnection();
        $query = "SELECT COUNT(*) FROM blogposts WHERE PostID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }

    public function deletePost($postId) {
        // Sanitize input
        $postId = $this->sanitizeInput($postId);

        // Delete an existing blog post using prepared statement
        $conn = $this->db->getConnection();
        $query = "DELETE FROM blogposts WHERE PostID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}


class Comment {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAllCommentsForPost($postId) {
        // Retrieve all comments for a specific blog post
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM comments WHERE PostID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $comments = [];

        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }

        return $comments;
    }

    public function addComment($postId, $userId, $comment, $name) {
        // Add a new comment to a blog post
        $conn = $this->db->getConnection();
        $query = "INSERT INTO comments (PostID, UserID, CommentText, Name) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $postId, $userId, $comment,  $name);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}


?>