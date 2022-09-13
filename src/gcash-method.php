<?php
include_once 'includes/header.php';

?>
<style>
    .gcash-pay-btn {
        background:#0F4C75;
        color:white;
        padding:10px 40px;
        border-radius:30px;
        
    }
    .gcash-pay-btn:hover {
        color:white;
        background:#3F72AF;
    }
    .gcash-icon {
        border-radius:30px;
    }

    .total {
        font-size: 1.5rem;
        margin: 20px;
    }

</style>
<div class="container mx-auto text-center my-4 py-4">
    
    <?php
        if(!isset($_SESSION['user_id'])){
            header('Location: login');
            exit();
        }
        
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

        $_SESSION['cart_total'] = number_format((float)$total, 2, '.', '');
        
        
        include_once './gcash-request.php';
        

        $gcash_checkouturl = $gcash_response['data']['checkouturl'];
    ?>
    
    <img class="gcash-icon" src="./images/gcash-icon.png" width="200" alt="Pay with Gcash">
    <p class="total" ><b>Total:</b> <span>₱ <?= $_SESSION['cart_total'] ?></span></p>
    <a class="gcash-pay-btn" href="<?= $gcash_checkouturl ?>">Pay Now</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<?php include_once 'includes/footer.php';?>
