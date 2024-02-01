<?php
// Include database file
include('database.php');

// Check user logged In details
session_start();
$email = $_SESSION['email'];

$stmt = $conn->query("SELECT id,username FROM users  WHERE email= '".$email."'");

$row=array();
$row = $stmt->fetch_assoc();

?>

<body>
<h1>Welcome <?php echo $row['username']; ?></h1>


<p>Thanks for creating your account with us<p>
</body>