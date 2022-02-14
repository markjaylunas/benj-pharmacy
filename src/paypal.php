<?php
include_once 'includes/header.php';
?>
<div class="paypal-container">
    
    <?php 
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
        $cart_total = $subtotal+$shipping_cost;
        $_SESSION['cart_total'] = number_format((float)$cart_total, 2, '.', '');;
    ?>
    
    <div class="a"><div class="b"><div class="b"><div class="d"><div class="e"><div class="f"><div class="g"><div class="h"><input type="hidden" id="tempT" value="<?= $_SESSION['cart_total'] ?>"></div></div></div></div></div></div></div></div>
    <div id="paypal-message" class="notif">
        <p id="message"></p>
        <p id="desc"></p>
    </div>
    <p id="paypal-total">Total: <span>₱ <?= $_SESSION['cart_total'] ?></span></p>
    <div id="paypal-button-container">
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://www.paypal.com/sdk/js?client-id=AZq8g2aSTgrXiRVsBI2ZH4Z4hjTIzmJtwAuRIhvEA9EG_uAflsPsX92DuA57-lUKGbN07L-2v0D7AxLF&buyer-country=PH&currency=PHP"></script>
<script src="./js/paypal.js"></script>
<?php include_once 'includes/footer.php';?>
