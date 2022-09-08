<?php
include('./dbh.inc.php');
session_start();

if(isset($_POST['add_to_cart'])){
    if($_POST['product_quantity']<=0){
        $_SESSION['message'] = 'Add to cart Failed! - Product quantity must be greater than 0';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    $user_id = $_POST['user_id'];
    if(!$user_id){
        header('Location: ../login');
        exit();
    }
    $product_id = $_POST['product_id'];
    $quantity = $_POST['product_quantity'];

    $check_sql = "SELECT * FROM cart WHERE product_id='$product_id' AND `user_id`='$user_id'";
    $check_stmt = mysqli_query($conn, $check_sql);
    if(mysqli_num_rows($check_stmt)>0){
        $res = mysqli_fetch_array($check_stmt);
        $db_quantity = $res['quantity'];
        $new_quantity = $db_quantity + $quantity;
        echo $new_quantity;
        $update_sql = "UPDATE cart  SET `quantity`='$new_quantity' WHERE `product_id`='$product_id' AND `user_id`='$user_id'";

        $update_stmt = mysqli_query($conn, $update_sql);
        if($update_stmt){
            $_SESSION['message'] = 'Already added to cart';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }else{
            $_SESSION['message'] = 'Something went wrong';
            // header('Location: ' . $_SERVER['HTTP_REFERER']);
            // exit();
        }
    }else{
        // Add to cart table
        $new_sql = "INSERT INTO `cart` (`user_id`, `product_id`, `quantity`) VALUES ('$user_id', '$product_id', '$quantity')";
        $new_stmt = mysqli_query($conn, $new_sql);
        if($new_stmt){
            $_SESSION['message'] = 'Added to cart';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }else{
            $_SESSION['message'] = 'Something went wrong';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }    
        echo 'none';
    }
}


if(isset($_POST['cart_item_delete'])){
    $cart_id = $_POST['cart_item_delete'];
    $user_id = $_POST['user_id'];
    
    
    $delete_sql = "DELETE FROM cart WHERE cart_id='$cart_id' AND `user_id`='$user_id'";
    $delete_stmt = mysqli_query($conn, $delete_sql);
    if($delete_stmt){
        // $_SESSION['message'] = 'Deleted from cart';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['cart_all_item_delete'])){
    $user_id = $_POST['cart_all_item_delete'];
    
    $delete_sql = "DELETE FROM cart WHERE `user_id`='$user_id' AND checked='1'";
    $delete_stmt = mysqli_query($conn, $delete_sql);
    if($delete_stmt){
        // $_SESSION['message'] = 'Deleted from cart';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

if(isset($_POST['new_address'])){
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $address_sql = "INSERT INTO address (user_id, address, city, barangay, contact, notes) VALUES
    ('$user_id', '$address', '$city', '$barangay', '$contact', '$notes')";
    $address_stmt = mysqli_query($conn, $address_sql);

    if($address_stmt){
        header('Location: ../cart');
        exit();
    }else{
        header('Location: ../cart');
        exit();
    }
}
if(isset($_POST['update_address'])){
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $address_sql = "UPDATE address SET `user_id`='$user_id', `address`='$address', `city`='$city', `barangay`='$barangay', `contact`='$contact', `notes`='$notes' WHERE `user_id`='$user_id'";
    $address_stmt = mysqli_query($conn, $address_sql);

    if($address_stmt){
        header('Location: ../cart');
        exit();
    }else{
        header('Location: ../cart');
        exit();
    }
}
