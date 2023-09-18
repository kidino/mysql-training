<?php 
// set some caching rule to prevent browser from caching protected pages
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(!isset($_SESSION['loggedin']) || ($_SESSION['loggedin'] !== true)) {
    $msg = urlencode('You do not have access to this page or your session has expired.');
    header('Location: login.php?error='.$msg);
    exit;
}