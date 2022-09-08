<?php
include_once './dbh.inc.php';
session_start();


if(isset($_POST['quantity_update'])){
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity_update'];
    
    $quantity_sql = "UPDATE cart SET quantity='$quantity' WHERE cart_id='$cart_id'";
    $quantity_stmt = mysqli_query($conn, $quantity_sql);
} 

if(isset($_POST['cart_checked'])){
    $cart_id = $_POST['cart_id'];
    $cart_checked = $_POST['cart_checked'];
    
    $checked_sql = "UPDATE cart SET checked='$cart_checked' WHERE cart_id='$cart_id'";
    $checked_stmt = mysqli_query($conn, $checked_sql);
}

if(isset($_POST['cart_summary_update'])){
    if(isset($_SESSION['user_id'])){
        $result = array();
        $user_id = $_SESSION['user_id'];
        
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
            }
            // remove shipping from cart *update*
            $shipping_cost = 0;
        }else{
            $shipping_cost = 0;
        }
        $summary_result = array('subtotal' => $subtotal, 
            'shipping_cost' => $shipping_cost, 
            'total' => $subtotal+$shipping_cost); 
        echo json_encode($summary_result);
    }
}

if(isset($_POST['checkout_summary_update'])){
    if(isset($_SESSION['user_id'])){
        $result = array();
        $user_id = $_SESSION['user_id'];
        $delivery_option = $_POST['delivery_option'];

        $shipping_cost;
        $shipping_sql = "SELECT cost FROM shipping_fee WHERE shipping_id=1 LIMIT 1";
        $shipping_stmt = mysqli_query($conn, $shipping_sql);
        if($shipping_stmt){
            $shipping_cost = mysqli_fetch_assoc($shipping_stmt)['cost'];
            if($delivery_option === 'pickup') $shipping_cost = 0;
        }

        $discount_approval;
        $discount_approval_sql = "SELECT approval FROM discounts WHERE user_id='$user_id' LIMIT 1";
        $discount_approval_stmt = mysqli_query($conn, $discount_approval_sql);
        if($discount_approval_stmt){
            $discount_approval = mysqli_fetch_assoc($discount_approval_stmt)['approval'];
        }


        $summary_sql = "SELECT p.price, c.quantity FROM products p, cart c WHERE c.user_id = '$user_id' AND p.product_id=c.product_id AND c.checked = 1";
        $summary_stmt = mysqli_query($conn, $summary_sql);
        if($summary_stmt){
            $subtotal = 0;
            foreach($summary_stmt as $row){
                $subtotal = $subtotal + $row['price']*$row['quantity'];
            }
        }
        
        if ( $discount_approval === '1' ){
            $summary_result = array(
                'discount_approval'   => $discount_approval,

                'vat_exempt_sale'   => $subtotal/1.12,
                'discount_total'   => ($subtotal/1.12)*0.20,
                'total'   => ($subtotal/1.12)-(($subtotal/1.12)*0.20)+($shipping_cost),

                'subtotal'          => $subtotal, 
                'shipping_cost'     => $shipping_cost
            );

        }else{
            $summary_result = array(
                'discount_approval'   => $discount_approval,

                'subtotal'      => $subtotal, 
                'shipping_cost' => $shipping_cost, 
                'discount_total' => 0.00, 
                'total'         => $subtotal+$shipping_cost
            ); 
        }
        
        echo json_encode($summary_result);
    }
}


if(isset($_POST['checkout'])){
    if(isset($_SESSION['user_id'])){
        $result = array();
        $user_id = $_SESSION['user_id'];
        
        $checkout_sql = "SELECT checked FROM cart WHERE `user_id` = '$user_id' AND checked=1";
        $checkout_stmt = mysqli_query($conn, $checkout_sql);
        if(mysqli_num_rows($checkout_stmt)>0){
            $result = 'true';
        }else{
            $result = 'false';
        }
        $summary_result = array('result' => $result); 
        echo json_encode($summary_result);
    }
}