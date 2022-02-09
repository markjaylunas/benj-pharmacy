<?php
ob_start();
session_start();
if(isset($_POST['logout_btn'])){
    // session_destroy();
    unset($_SESSION['auth_user']); 
    $_SESSION['message'] = "Logged out succesfully";
    header("Location: login");
    exit();
}
?>