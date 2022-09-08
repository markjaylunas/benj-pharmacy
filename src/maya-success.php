<?php
session_start();
include('./includes/dbh.inc.php');
if(isset($_SESSION['user_id'])){
    // $data = '';
    // if($_GET['data'] != '' & $_GET['data']!= null){
    //     $data = json_decode($_GET['data']);
    //     $raw_data = $_GET['data'];
    //     if($data->status == "COMPLETE"){
    //         echo $data->status;
    //     }
    //     else{
    //         header('Location: maya?error=redirect_incomplete');
    //         exit();
    //     }
    // }else{
    //     header('Location: maya?error=redirect_no_data');
    //     exit();
    // }
    
    $raw_data = "temporary data";

    $user_id = $_SESSION['user_id'];
    $delivery_option = '';
    // delivery_option
    $delivery_option_sql = "SELECT * FROM checkout WHERE user_id = '$user_id' LIMIT 1";
    $delivery_option_stmt = mysqli_query($conn, $delivery_option_sql);
    if(mysqli_num_rows($delivery_option_stmt)>0){
        foreach($delivery_option_stmt as $row){
            $delivery_option = $row['delivery_option'];
        }
    }else{
        $delivery_option = 'none';
    }
    $total = 0.00;
    $subtotal = 0.00;
    $shipping_cost = 0.00;
    $discounted = 0.00;
    $vat_exempt_subtotal = 0.00;
    $discount_total = 0.00;
    
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

    if($delivery_option === 'pickup'){
        $shipping_cost = 0;
    }
    $user_discount_approval = '0' ;
    $user_discount_sql      = "SELECT approval FROM discounts WHERE user_id='$user_id' LIMIT 1";
    $user_discount_stmt     = mysqli_query($conn, $user_discount_sql);
    if(mysqli_num_rows($user_discount_stmt)>0){
        $user_discount_approval = mysqli_fetch_assoc($user_discount_stmt)['approval'];
    }

    // COMPUTATION
    if ( $user_discount_approval === '1' ){
        $discounted          = 1;
        $vat_exempt_subtotal = $subtotal/1.12;
        $discount_total      = ($subtotal/1.12)*0.20;
        $total               = ($subtotal/1.12)-(($subtotal/1.12)*0.20)+($shipping_cost);
        $subtotal            = $subtotal; 
        $shipping_cost       = $shipping_cost;
        
    }else{
        $discounted             = '0';
        $vat_exempt_subtotal    = 0.00;
        $discount_total         = 0.00;
        
        $total                  = $subtotal+$shipping_cost;
        $subtotal               = $subtotal;
        $shipping_cost          = $shipping_cost;
    }

    $cart_total = number_format((float)$total, 2, '.', '');
    // echo $_GET['customername'];
    // echo $_GET['success'];
    // echo $_SESSION['maya_response'];
    $order_id = uniqid();

    // check for existing order id
    $check_sql = "SELECT * FROM orders WHERE order_id='$order_id' LIMIT 1"; 
    $check_stmt = mysqli_query($conn, $check_sql);
    if(mysqli_num_rows($check_stmt)>0){
        $order_id = uniqid();
    }


    // create order 
    $order_sql = "INSERT INTO orders (`order_id`, `total`, `subtotal`, `vat_exempt_subtotal`, `discount`, `discounted`, `paid`, `payment_method`, `delivery_option`, `shipping_fee`, `user_id`) VALUES ('$order_id', '$total', '$subtotal', '$vat_exempt_subtotal', '$discount_total', '$discounted', '1', 'maya', '$delivery_option', '$shipping_cost', '$user_id')"; 
    $order_stmt = mysqli_query($conn, $order_sql);
    if($order_stmt){
        echo "order_stmt";
        $orders_products_sql = "INSERT INTO orders_products (user_id, order_id, product_id, name, price, quantity)
        SELECT '$user_id' AS user_id, '$order_id' AS order_id, c.product_id, p.name, p.price, c.quantity FROM cart c, products p WHERE c.user_id='$user_id' AND c.checked='1' AND p.product_id=c.product_id";
        $orders_products_stmt = mysqli_query($conn, $orders_products_sql);
        if($orders_products_stmt){
            echo "orders_products_stmt";

            $delete_sql = "DELETE FROM cart WHERE `user_id`='$user_id' AND checked='1'";
            $delete_stmt = mysqli_query($conn, $delete_sql);
            if($delete_stmt){
                echo "delete_stmt";

                
                $maya_sql = "INSERT INTO maya_data (`data`, `order_id`, `user_id`) VALUES ('$raw_data', '$order_id', '$user_id')"; 
                $maya_stmt = mysqli_query($conn, $maya_sql);

                if($maya_stmt){
                    echo 'success insert maya data';
                    header('Location: place-order?method=maya&id='.$order_id);
                    exit();
                }else{
                    echo 'failed to insert maya data';
                    exit();
                }
            }else{
                echo 'failed to delete last cart';
                exit();
            }
        }else{
            echo 'failed add product_order id';
        }
    }else{
        echo 'failed';
        // header('Location: maya');
        // exit();
    }
}else{
    header('Location: login?error=redirect_no_user');
    exit();
}