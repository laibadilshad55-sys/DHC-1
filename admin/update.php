<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
}
?>

<h2>Update System Data</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Update Title">
    <button>Update</button>
</form>