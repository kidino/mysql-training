<?php 
session_start();
// remove loggedin session status
unset($_SESSION['loggedin']);

// redirecting to login page
$msg = urlencode("You have been logged out. Thank you.");
header('Location: login.php?message='.$msg);