<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include '../config/db.php';

$app_id = $_POST['app_id'];
$status = $_POST['status'];

// Update application status
$conn->query("UPDATE applications SET status = '$status' WHERE id = '$app_id'");

// Redirect back to view page with success message
header("Location: view_application.php?id=$app_id&msg=Status updated to " . ucfirst($status));
exit();
?>