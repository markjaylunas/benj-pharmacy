<?php 
session_start();
include('./includes/dbh.inc.php');
if (isset($_SESSION['user_id'])){
    $user_id = $_GET['id'];
    
    if (isset($_GET['method'])){
        $method = $_GET['method'];
        if($method == 'cod'){
            $total = 0;
            $subtotal = 0;
            $shipping_cost = 0;
            $summary_sql = "SELECT p.price, c.quantity FROM products p, cart c WHERE c.user_id = '$user_id' AND p.product_id=c.product_id AND c.checked = 1";
            $summary_stmt = mysqli_query($conn, $summary_sql);
            
            if($summary_stmt){
                $subtotal = 0;
                foreach($summary_stmt as $row){
                    $subtotal = $subtotal + $row['price']*$row['quantity'];
                }
            }
            
            if($subtotal>0){
                $shipping_sql = "SELECT cost FROM shipping_fee WHERE shipping_id=1 LIMIT 1";
                $shipping_stmt = mysqli_query($conn, $shipping_sql);
                if($shipping_stmt){
                    $shipping_cost = mysqli_fetch_assoc($shipping_stmt)['cost'];
                }else{
                    $shipping_cost = 0;
                }
            }else{
                $subtotal = 0;
            }
            // TOTAL
            $total = $subtotal+$shipping_cost;
            $order_id = uniqid();

            // check for existing order id
            $check_sql = "SELECT * FROM orders WHERE order_id='$order_id' LIMIT 1"; 
            $check_stmt = mysqli_query($conn, $check_sql);
            if(mysqli_num_rows($check_stmt)>0){
                $order_id = uniqid();
            }
            // create order 
            $order_sql = "INSERT INTO orders (`order_id`, `total`, `paid`, `payment_method`, `shipping_fee`, `user_id`) VALUES ('$order_id', '$total', '0', '$method', '$shipping_cost', '$user_id')"; 
            $order_stmt = mysqli_query($conn, $order_sql);
            if($order_stmt){
                $orders_products_sql = "INSERT INTO orders_products (user_id, order_id, product_id, name, price, quantity)
                SELECT '$user_id' AS user_id, '$order_id' AS order_id, c.product_id, p.name, p.price, c.quantity FROM cart c, products p WHERE c.user_id='$user_id' AND c.checked='1' AND p.product_id=c.product_id";
                $orders_products_stmt = mysqli_query($conn, $orders_products_sql);
                if($orders_products_stmt){
                    $delete_sql = "DELETE FROM cart WHERE `user_id`='$user_id' AND checked='1'";
                    $delete_stmt = mysqli_query($conn, $delete_sql);
                    if($delete_stmt){
                        echo 'success delete last cart';
                        header('Location: place-order?id='.$order_id);
                        exit();
                    }else{
                        echo 'failed to delete last cart';
                        exit();
                    }
                }else{
                    echo 'failed add product_order id';
                }

                
            }else{
                echo 'failed';
            }
        }elseif($method == 'gcash'){
            header('Location: 404.php?error=commingsoon');
        }else{
            echo 'no such method';
            exit();
        }

    }else{
        echo 'no method';
        exit();
    }
    
}else{
    echo 'error';
    exit();
}
