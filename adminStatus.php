<?php
include '../config/db.php';

$app_id = $_GET['id'];
$status = $_GET['status'];

$conn->query("UPDATE applications SET status='$status' WHERE id='$app_id'");
?>