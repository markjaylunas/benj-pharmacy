<?php
include('./config/dbcon.php');
if($_GET['id']){
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT exclusive FROM pages_status WHERE product_id = $product_id LIMIT 1;";
    $stmt = mysqli_query($conn, $sql);
    if(mysqli_num_rows($stmt)){
        $current_exclusive = mysqli_fetch_array($stmt)[0];

        $new_exclusive = 0;
        if($current_exclusive == 0){
            $new_exclusive = 1;
        }
        $page_sql = "UPDATE pages_status SET exclusive = $new_exclusive WHERE product_id = $product_id";
        $page_stmt = mysqli_query($conn, $page_sql);
        if($page_stmt){
            $_SESSION['message'] = ' Exclusive Modified Succesfully';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }else{
            $_SESSION['message'] = 'Something went wrong';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
        
    }else{
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}