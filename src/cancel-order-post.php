<?php
if(!isset($_POST['cancel_confirmed'])||!isset($_POST['user_id'])||!isset($_POST['order_id'])){
    header('Location: account?error=cancel-order-post-block');
    exit();
}

$user_id = $_POST['user_id'];
$order_id = $_POST['order_id'];
$reason = $_POST['cancellation_reason'];

require_once "./includes/dbh.inc.php";

$check_existing_sql = "SELECT * FROM cancellation WHERE order_id='$order_id';";
$check_existing_stmt = mysqli_query($conn, $check_existing_sql);
if(mysqli_num_rows($check_existing_stmt)>0){
    header('Location: account?error=cancel-order-post-existed-ejected');
    exit();
}

$insert_reason_sql = "INSERT INTO cancellation (`order_id`, `reason`) VALUES ('$order_id', '$reason')"; 

$insert_reason_stmt = mysqli_query($conn, $insert_reason_sql);
if($insert_reason_stmt){
    $update_order_cancel_sql = "UPDATE orders SET cancelled='1' WHERE order_id = '$order_id' LIMIT 1";
    $update_order_cancel_stmt = mysqli_query($conn, $update_order_cancel_sql);
    if($update_order_cancel_stmt){
        header('Location: account?error=cancel-order-post-updated-onprocess');
        exit();
    }else{
        header('Location: account?error=cancel-order-post-onprocess-update-failed');
        exit();
    }
    
}else{
    header('Location: account?error=cancel-order-post-insert-failed');
    exit();
}





?>