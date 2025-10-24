<?php

include 'connect.php';


session_start();

// Clear user-specific session variables or selectively unset them
// For example: unset($_SESSION['user_id']);
session_unset();
// session_destroy();

// Set the logout success message
$_SESSION['logout_success'] = true;

// Redirect to the home page where you check for this session variable
header('location:../admin/admin-login.php');

?>