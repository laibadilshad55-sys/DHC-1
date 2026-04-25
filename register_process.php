<?php
include("config/db.php");

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$query = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
mysqli_query($conn, $query);

header("Location: login.php");
?>