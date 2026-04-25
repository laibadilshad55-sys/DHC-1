<?php
session_start();
include '../config/db.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Query admin user
$result = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'");

if($result && $result->num_rows > 0){
    $admin = $result->fetch_assoc();
    
    if(password_verify($password, $admin['password'])){
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['admin_login_error'] = "Invalid password";
    }
} else {
    $_SESSION['admin_login_error'] = "Admin email not found";
}

header("Location: login.php");
exit();
?>