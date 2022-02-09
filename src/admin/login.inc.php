<?php 
ob_start();
session_start();
include('config/dbcon.php');
if(isset($_POST['admin_login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM admin WHERE admin_username = '$username' AND admin_pass = '$password' LIMIT 1;";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res)){
        foreach($res as $data){
            $admin_id = $data['admin_id'];
            $admin_name = $data['admin_username'];
            $admin_email = $data['admin_email'];
        }
        session_start();
        $_SESSION['auth_admin'] = [
            'admin_id'=>$admin_id,
            'admin_name'=>$admin_name,
            'admin_email'=>$admin_email,
        ];        
        $_SESSION['message'] = 'Welcome to dashboard';
        header('Location: index');
        exit();
    }else{
        $_SESSION['message'] = "Invalid username or password";
        header("Location: login");
        exit();
    }
}else{
    $_SESSION['message'] = "You are not allowed to access this";
    header('Location: login.');
    exit();
}?>