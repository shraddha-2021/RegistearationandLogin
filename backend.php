<?php
// Include Database file
include('database.php');


// user registeration form
if ($_POST['action'] == 'create_user') {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);
    
    //Validation check and Error messages
    $errors = [];

    // Name Validation
    if (!preg_match("/^[a-zA-Z ]*$/", $username)) {
        $errors[] ="Please enter name.";
    } 

    // Email Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] ="Plese enter valid email address.";
    }

    // Phone Number Validation
    if (!preg_match('/^[0-9]{10}+$/', $phone)) {
        $errors[] ="Please enter valid phone number.";
    }

    // Password Validation
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $_POST['password'])) {
        $errors[] ="Invalid Password. Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }
   
    
    // Display error messages if any
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else 
    {
        // Insert the user information into database
        $stmt = $conn->prepare("INSERT INTO users (username,email,phone,password) VALUES (?, ?, ?,?)");
        $stmt->bind_param("ssss", $username, $email,$phone,$password);

        if ($stmt->execute()) {
            echo "User created successfully";
        } else {
            echo "Error creating user: " . $stmt->error;
        }

    
    $stmt->close();
    }
}



if ($_POST['action'] == 'login_user') {
   
  
    $email = $_POST['email'];  
    
    $password= md5($_POST['password']);

    // Validation check 
    $errors = [];
    // Email Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] ="Plese enter valid email address.";
    }

    if($_POST['password']==''){
        $errors[] ="Plese enter valid password.";
    }

     // Display error messages if any
     if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else 
    {
    $stmt = $conn->prepare("SELECT email,password FROM users  WHERE email= ? AND password=?");

    $stmt->bind_param('ss',$email,$password);

    $stmt->execute();
    
    $stmt->bind_result($email, $password);

    $stmt->store_result();

     if ($stmt->num_rows == 1) {
        
    $sessionid=1 ;
    $stmt = $conn->prepare("Update  users SET sessionid= ?  WHERE email= ? ");
    $stmt->bind_param("ss",$sessionid,$email);
    $stmt->execute();

    session_start();
    $_SESSION['email']= $email;
        echo "User loggedIn successfully";
    } else {
        echo "Invalid credentials";
    }

    $stmt->close();
    }
}

// Close database connection
$conn->close();



?>