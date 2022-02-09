<?php
ob_start();
session_start();
if(!isset($_SESSION['auth_admin'])){
    $SESSION['message'] = 'Log in to access dashboad';
    header('Location: login');
    exit();
}
?>