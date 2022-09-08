<?php
ob_start();
include('../authentication.php');
include('../config/dbcon.php');
function slugify($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, $divider);
    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);
    // lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}


if(isset($_POST['user_update'])){
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
        
    $sql = "UPDATE users SET first_name='$fname', last_name='$lname', email='$email' WHERE user_id='$user_id'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'User updated succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'User pdate failed';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['user_delete'])){
    $user_id = $_POST['user_delete'];
    $sql = "DELETE FROM users WHERE user_id='$user_id' LIMIT 1";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'Deleted Succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['category_add'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if($_POST['slug']){
        $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    }else{
        $slug = slugify($name);
    }
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keyword = mysqli_real_escape_string($conn, $_POST['meta_keyword']);
    $sql = "INSERT INTO categories (name, slug, description, meta_title, meta_description, meta_keyword) VALUES
        ('$name', '$slug', '$description','$meta_title', '$meta_description','$meta_keyword')";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'Category added succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['category_update'])){
    $category_id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if($_POST['slug']){
        $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    }else{
        $slug = slugify($name);
    }
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keyword = mysqli_real_escape_string($conn, $_POST['meta_keyword']);
    $sql = "UPDATE categories SET name='$name', slug='$slug', description='$description', meta_title='$meta_title', meta_description='$meta_description', meta_keyword='$meta_keyword' WHERE category_id='$category_id'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'Category updated succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'Category update failed';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['category_delete'])){
    $id = $_POST['category_delete'];
    $sql = "DELETE FROM categories WHERE category_id='$id LIMIT 1'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'Deleted Succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['product_add'])){
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if($_POST['slug']){
        $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    }else{
        $slug = slugify($name);
    }
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $specification = mysqli_real_escape_string($conn, $_POST['specification']);
    
    $image = $_FILES['image']['name'];
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time().'.'.$image_extension;
    if(!isset($_POST['status'])){
        $status = 0;
    }else{
        $status = $_POST['status'] == true? 1:0;
    }
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keyword = mysqli_real_escape_string($conn, $_POST['meta_keyword']);
    $sql = "INSERT INTO products (category_id, name, slug, price, stock, description, specification, image, meta_title, meta_description, meta_keyword, status) VALUES
        ('$category_id', '$name', '$slug', '$price', '$stock', '$description', '$specification', '$filename', '$meta_title', '$meta_description', '$meta_keyword', '$status')";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        // manage uploaded image
        move_uploaded_file($_FILES['image']['tmp_name'], '../../uploads/products/'.$filename);
        //initialize page status
        $last_id = mysqli_insert_id($conn);
        $page_sql = "INSERT INTO pages_status (product_id) VALUES ('$last_id')";
        $page_stmt = mysqli_query($conn, $page_sql);
        if($page_stmt){
            $_SESSION['message'] = 'Product Added Succesfully';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }else{
            $_SESSION['message'] = 'Something went wrong on page';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
        
    }else{
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['product_update'])){
    $product_id = $_POST['product_id'];
    
    $category_id = $_POST['category_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if($_POST['slug']){
        $slug = $_POST['slug'];
    }else{
        $slug = slugify($name);
    }
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $specification = mysqli_real_escape_string($conn, $_POST['specification']);

    // $image = $_FILES['image']['name'];
    // $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    // $filename = time().'.'.$image_extension;

    $old_filename = $_POST['old_image'];  
    $image = $_FILES['image']['name'];

    $update_filename = '';  
    if($image != NULL){
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time().'.'.$image_extension;
        $update_filename = $filename;
    }else{
        $update_filename = $old_filename;
    }
    
    $status = $_POST['status'] == true ? '1':'0';
    $stock = $_POST['stock'];
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keyword = mysqli_real_escape_string($conn, $_POST['meta_keyword']);
    $sql = "UPDATE products SET category_id='$category_id', name='$name', slug='$slug', price='$price', stock='$stock', description='$description', specification='$specification', image='$update_filename', meta_title='$meta_title', meta_description='$meta_description', meta_keyword='$meta_keyword', status='$status' WHERE product_id='$product_id'";
    $stmt = mysqli_query($conn, $sql);
    
    if($stmt){
        if($image != NULL){
            if(file_exists('../../uploads/products/'.$old_filename)){
                unlink("../../uploads/products/".$old_filename);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], '../../uploads/products/'.$update_filename);
        }else{
        }
        $_SESSION['message'] = 'Product updated Succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'Product update failed';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
if(isset($_POST['product_delete'])){
    $id = $_POST['product_delete'];
    $image = $_POST['image_delete'];
    $sql = "DELETE FROM products WHERE product_id='$id'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        if(file_exists('../../uploads/products/'.$image)){
            unlink("../../uploads/products/".$image);
        }
        $page_sql = "DELETE FROM pages_status WHERE product_id='$id' LIMIT 1";
        $page_stmt = mysqli_query($conn, $page_sql);
        if($stmt){
            $_SESSION['message'] = 'Product deleted succesfully';
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
if(isset($_POST['shipping_fee_update'])){
    $shipping_fee = $_POST['shipping_fee'];
    $sql = "UPDATE `shipping_fee` SET cost='$shipping_fee' WHERE shipping_id='1'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'Shipping fee updated succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'Something went wrong';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

if(isset($_POST['about_update'])){
    $about_id = '1';
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $old_filename = $_POST['old_image'];  
    $image = $_FILES['image']['name'];

    $update_filename = '';  
    if($image != NULL){
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time().'.'.$image_extension;
        $update_filename = $filename;
    }else{
        $update_filename = $old_filename;
    }

    $sql = "UPDATE about_us SET title='$title', description='$description', image='$update_filename' WHERE about_id='$about_id'";
    $stmt = mysqli_query($conn, $sql);
    
    if($stmt){
        if($image != NULL){
            if(file_exists('../../uploads/about_us/'.$old_filename)){
                unlink("../../uploads/about_us/".$old_filename);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], '../../uploads/about_us/'.$update_filename);
        }
        $_SESSION['message'] = 'About Us Page updated succesfully';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }else{
        $_SESSION['message'] = 'About Us Page update failed';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}


if(isset($_POST['order_status_update'])){
    $status = $_POST['status'];
    $order_id = $_POST['order_id'];
    
    $sql = "UPDATE orders SET delivery_status='$status' WHERE order_id='$order_id'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        
        if ($status === '0'){
            echo "true";

            $select_sql = "SELECT * FROM orders_products WHERE order_id='$order_id' AND stock_minus='1'";
            $select_stmt = mysqli_query($conn, $select_sql);
            if(mysqli_num_rows($select_stmt)>0){
                foreach($select_stmt as $row){
                    echo ' - ';
                    echo $row['name'];

                    $product_id =  $row['product_id'];
                    $product_quantity =  $row['quantity'];
                    $update_sql = "UPDATE products SET stock=stock+'$product_quantity' WHERE product_id='$product_id'";
                    $update_stmt = mysqli_query($conn, $update_sql);
                    $update_stock_minus_sql = "UPDATE orders_products SET stock_minus='0' WHERE product_id='$product_id' AND order_id='$order_id'";
                    $update_stock_minus_stmt = mysqli_query($conn, $update_stock_minus_sql);
                }
            }
            
        }else{
            echo "false";

            $select_sql = "SELECT * FROM orders_products WHERE order_id='$order_id' AND stock_minus='0'";
            $select_stmt = mysqli_query($conn, $select_sql);
            if(mysqli_num_rows($select_stmt)>0){
                
                foreach($select_stmt as $row){
                    $product_id =  $row['product_id'];
                    $product_quantity =  $row['quantity'];
                    $update_sql = "UPDATE products SET stock=stock-'$product_quantity' WHERE product_id='$product_id'";
                    $update_stmt = mysqli_query($conn, $update_sql);
                    $update_stock_minus_sql = "UPDATE orders_products SET stock_minus='1' WHERE product_id='$product_id' AND order_id='$order_id'";
                    $update_stock_minus_stmt = mysqli_query($conn, $update_stock_minus_sql);
                }
            }
            
        }
        $_SESSION['message'] = 'Status updated succesfully';
        header("Location: ../order-view-list?id=$order_id");
        exit();
    }else{
        $_SESSION['message'] = 'Status update failed';
        header("Location: ../order-view-list?id=$order_id");
        exit();
    }
}

if(isset($_POST['order_paid_update'])){
    $paid = $_POST['paid'];
    $order_id = $_POST['order_id'];
    echo 'paid :'.$paid;
    echo 'id :'.$order_id;
    $sql = "UPDATE orders SET paid='$paid' WHERE order_id='$order_id'";
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
        $_SESSION['message'] = 'Paid Status updated succesfully';
        header("Location: ../order-view-list?id=$order_id");
        exit();
    }else{
        $_SESSION['message'] = 'Paid Status update failed';
        header("Location: ../order-view-list?id=$order_id");
        exit();
    }
}

if(isset($_POST['order_cancellation_update'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    echo 'order_id :'.$order_id;
    echo 'status :'.$status;
    

    $cancellation_update_sql = "UPDATE cancellation SET status='$status' WHERE order_id='$order_id'";
    $cancellation_update_stmt = mysqli_query($conn, $cancellation_update_sql);
    if($cancellation_update_stmt){
        if($status==='2'){
            $order_cancelled_update_sql = "UPDATE orders SET cancelled='2' WHERE order_id='$order_id'";
            $order_cancelled_update_stmt = mysqli_query($conn, $order_cancelled_update_sql);
            if($order_cancelled_update_stmt){
                $_SESSION['message'] = 'Both Cancellation Status updated successfully';
                header("Location: ../order-cancelled-view?id=$order_id");
                exit();
            }else{
                $_SESSION['message'] = 'Cancelled Order Status update failed';
                header("Location: ../order-cancelled-view?id=$order_id");
                exit();
            }
        }else{
            $_SESSION['message'] = 'Cancellation Status updated successfully';
            header("Location: ../order-cancelled-view?id=$order_id");
            exit();
        }
    }else{
        $_SESSION['message'] = 'Cancellation update failed';
        header("Location: ../order-cancelled-view?id=$order_id");
        exit();
    }
}


if(isset($_POST['discount_status_update'])){
    $user_id = $_POST['user_id'];
    $approval = $_POST['approval'];

    $discounts_update_sql = "UPDATE discounts SET approval='$approval' WHERE user_id='$user_id'";
    $discounts_update_stmt = mysqli_query($conn, $discounts_update_sql);
    if($discounts_update_stmt){
        $_SESSION['message'] = 'Approval update success';
        header("Location: ../discount-edit?id=$user_id");
        exit();
        
    }else{
        $_SESSION['message'] = 'Approval update failed';
        header("Location: ../discount-edit?id=$user_id");
        exit();
    }
}

