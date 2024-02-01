<?php
// Connection 
$conn = new mysqli("localhost", "root", "", "user_management_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>