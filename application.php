<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

$conn->query("INSERT INTO applications (user_id) VALUES ('$user_id')");
$app_id = $conn->insert_id;

// File upload
$file = $_FILES['doc']['name'];
$tmp = $_FILES['doc']['tmp_name'];

move_uploaded_file($tmp, "../uploads/".$file);

$conn->query("INSERT INTO documents (application_id,file_name,file_path)
VALUES ('$app_id','$file','uploads/$file')");
?>