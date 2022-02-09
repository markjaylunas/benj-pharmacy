<?php include_once 'includes/header.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    
    $summary_sql = "SELECT p.price, c.quantity FROM products p, cart c WHERE c.user_id = '$user_id' AND p.product_id=c.product_id AND c.checked = 1";
    $summary_stmt = mysqli_query($conn, $summary_sql);
    if($summary_stmt){
        $subtotal = 0;
        foreach($summary_stmt as $row){
            $subtotal = $subtotal + $row['price']*$row['quantity'];
        }
        $_SESSION['subtotal'] = $subtotal;
    }
    if($subtotal>0){
        $shipping_sql = "SELECT cost FROM shipping_fee WHERE shipping_id=1 LIMIT 1";
        $shipping_stmt = mysqli_query($conn, $shipping_sql);
        if($shipping_stmt){
            $shipping_cost = mysqli_fetch_assoc($shipping_stmt)['cost'];
            $_SESSION['shipping_cost'] = $shipping_cost;
        }
    }else{
        $_SESSION['shipping_cost'] = 0;
    }
}



?>

<div class="checkout-page">
    <div class="left">
    <div class="address-location">
        <h3>Location</h3>
        <div class="address">
            <i class="far fa-map-marker-alt"></i>
            <?php
                if(isset($_SESSION['user_id'])){
                    $user_id = $_SESSION['user_id'];
                    $address_sql = "SELECT * FROM address WHERE user_id='$user_id' LIMIT 1";
                    $address_stmt = mysqli_query($conn, $address_sql);
                    if(mysqli_num_rows($address_stmt)>0){
                        foreach($address_stmt as $address_row){
                            $address = $address_row['address'];
                        }
                        ?>
                        <p><?= $address ?></p>
                        <?php
                    }else{
                        $address = false;
                        ?>
                        <p>No Address</p>
                        <?php
                    }
                }
            ?>
        </div>
        <form action="./delivery-info<?= $address==false?'':'-update' ?>?id=<?= $_SESSION['user_id']; ?>" method="post">
            <button type="submit" class="btn_address" name="btn_address"><?= $address!=false?'Edit Address':'New Address' ?></button>
        </form>
    </div>
        <div class="orders">
        <?php 
            if(isset($_SESSION['user_id'])){
                $user_id = $_SESSION['user_id'];
                
                $cart_sql = "SELECT p.*,c.* FROM products p, cart c WHERE p.product_id=c.product_id AND c.user_id ='$user_id' AND c.checked='1';";
                $cart_stmt = mysqli_query($conn, $cart_sql);
                if(mysqli_num_rows($cart_stmt)>0){
                    foreach($cart_stmt as $cart_row){
                        $cart_id = $cart_row['cart_id'];
                        $user_id = $cart_row['user_id'];
                        ?>
                            <div class="order">
                                <img src="./uploads/products/<?= $cart_row['image'] ?>" alt="<?= $cart_row['name'] ?>">
                                <p class="product-name"><?= $cart_row['name'] ?></p>
                                <div class="product-price-summary">
                                    <p class="product-price">₱<?= $cart_row['price'] ?></p>
                                    <div class="quantity">
                                        <p class="checkout_p">Qty: <span><?= $cart_row['quantity'] ?></span></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                }else{
                    ?>
                    <div class="container d-flex flex-column justify-content-center align-items-center py-5">
                        <h4>No products Added</h4>
                        <a class="btn mt-5 btn-primary" href="./all-products">Shop now</a>
                    </div>
                    <?php
                }
            }
        ?>
        </div>
    </div>
    <div class="right">
        <div class="payment-section">
            <div class="cod">
                <div class="pay-left">
                    <i class="fas fa-truck-loading"></i>
                    <label>Cash on Delivery</label>
                    <p>Pay when you recieve</p>
                </div>
                <input type="radio" name="payment_method" class="payment_method_input" value="cod">
            </div>
            <div class="gcash">
                <div class="pay-left">
                    <img src="./images/gcash-icon.png"  alt="">
                    <label>GCash e-Wallet</label>
                    <p>Pay with GCash</p>
                </div>
                <input type="radio" name="payment_method" class="payment_method_input" value="gcash">
            </div>
        </div>

        <hr>
        <h3>Order Summary</h3>
        <div class="subtotal">
            <p>Subtotal</p>
            <p >₱ <span id="subtotal_value"><?= number_format((float)$_SESSION['subtotal'], 2, '.', '') ?></span></p>
        </div>
        <div class="subtotal">
            <p>Shipping Fee</p>
            <p >₱ <span id="shipping_value"><?= number_format((float)$_SESSION['shipping_cost'], 2, '.', '') ?></span></p>
        </div>
        <hr>
        <div class="total">
            <h3>Total</h3>
            <p>₱ <span id="total_value"><?= number_format((float)$_SESSION['subtotal']+$_SESSION['shipping_cost'], 2, '.', '') ?></span></p>
        </div>
        <input type="hidden" id="user_id" value="<?= $_SESSION['user_id']; ?>">
        <button class="place_order disabledButton">Place Order</button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        let refreshSummary = function(){ 
            $.ajax({
                url: 'includes/cart.inc.php',
                type: "POST",
                dataType: "json",
                data: {summary_update: true},
                success: function(result){
                    $('#subtotal_value').text((Math.round(result.subtotal * 100) / 100).toFixed(2));
                    $('#shipping_value').text((Math.round(result.shipping_cost * 100) / 100).toFixed(2));
                    $('#total_value').text((Math.round(result.total * 100) / 100).toFixed(2));
                },
            });
        }
        
        function paymentMethodSelected(radio,otherRadio){
            radio.prop('checked', !radio.prop('checked'));
            otherRadio.prop('checked', !radio.prop('checked'));
            var dotOtherRadio = `.${otherRadio.val()}`;
            var method = radio.val();
            var dotMethod = `.${method}`;
            if(radio.prop('checked')){
                $(dotMethod).css("border","1px solid #3F72AF")
                $(dotMethod).css("background-color","#B9D7EA")
                
                $(dotOtherRadio).css("border","1px solid black")
                $(dotOtherRadio).css("background-color","white")
                
                $('.place_order').addClass('activeButton');
            }else{
                $('.place_order').removeClass('disabledButton');
                $(dotMethod).css("border","1px solid black")
                $(dotMethod).css("background-color","white")
            }
            if(otherRadio.prop('checked')){
                $(dotOtherRadio).css("border","1px solid #3F72AF")
                $(dotOtherRadio).css("background-color","#B9D7EA")
                
                $(dotMethod).css("border","1px solid black")
                $(dotMethod).css("background-color","white")
                
                $('.place_order').addClass('activeButton');
            }else{
                $('.place_order').removeClass('disabledButton');
                $(dotOtherRadio).css("border","1px solid black")
                $(dotOtherRadio).css("background-color","white")
            }
            
        }
        $('.cod').on('click', function(){
            var radio = $(this).children('input[type="radio"]');
            var otherRadio = $('.gcash').children('input[type="radio"]');
            paymentMethodSelected(radio,otherRadio);
        });
        $('.gcash').on('click', function(){
            var radio = $(this).children('input[type="radio"]');
            var otherRadio = $('.cod').children('input[type="radio"]');
            paymentMethodSelected(radio,otherRadio);
        });

        $('.place_order').click(function(){
            if($(this).hasClass('disabledButton')){
                alert('Please select payment method')
            }
            if($(this).hasClass('activeButton')){
                var user_id = $('#user_id').val();
                var status = $('input[name="payment_method"]:checked').val();
                window.location = `place-order-post.php?method=${status}&id=${user_id}`;
            }
        });
        
    });
</script>

<?php include_once 'includes/footer.php'; ?>
