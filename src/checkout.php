<?php include_once 'includes/header.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    
    // Shipping fee
    $shipping_sql = "SELECT cost FROM shipping_fee WHERE shipping_id=1 LIMIT 1";
    $shipping_stmt = mysqli_query($conn, $shipping_sql);
    if(mysqli_num_rows($shipping_stmt)>0){
        $shipping_cost = mysqli_fetch_assoc($shipping_stmt)['cost'];
        $_SESSION['shipping_cost'] = $shipping_cost;
    }else{
        $_SESSION['shipping_cost'] = 0;
    }

    // discount
    $discount_sql = "SELECT * FROM discounts WHERE user_id = '$user_id' LIMIT 1";
    $discount_stmt = mysqli_query($conn, $discount_sql);
    if(mysqli_num_rows($discount_stmt)>0){
        foreach($discount_stmt as $row){
            $_SESSION['discount'] = $row['approval'];
        }
    }else{
        $_SESSION['discount'] = 'none';
    }
    
    
    
    // subtotal
    $summary_sql = "SELECT p.price, c.quantity FROM products p, cart c WHERE c.user_id = '$user_id' AND p.product_id=c.product_id AND c.checked = 1";
    $summary_stmt = mysqli_query($conn, $summary_sql);
    if(mysqli_num_rows($summary_stmt)>0){
        $subtotal = 0;
        foreach($summary_stmt as $row){
            $subtotal = $subtotal + $row['price']*$row['quantity'];
        }
        $_SESSION['subtotal'] = $subtotal;
        $_SESSION['total'] = number_format((float)$_SESSION['subtotal']+$_SESSION['shipping_cost'], 2, '.', '');
        
        if($_SESSION['discount']==='1'){
            $_SESSION['vat-exempt-sale'] = $subtotal/1.12;
            $_SESSION['discount-total'] = $_SESSION['vat-exempt-sale']*0.20;
            $_SESSION['discounted-total'] = $_SESSION['vat-exempt-sale']-$_SESSION['discount-total'];
            $_SESSION['total'] = $_SESSION['discounted-total'] + $_SESSION['shipping_cost'];
        }
    }else {
        header('Location: cart');
        exit();
    }
    
    // delivery_option
    $delivery_option_sql = "SELECT * FROM checkout WHERE user_id = '$user_id' LIMIT 1";
    $delivery_option_stmt = mysqli_query($conn, $delivery_option_sql);
    if(mysqli_num_rows($delivery_option_stmt)>0){
        foreach($delivery_option_stmt as $row){
            $_SESSION['delivery_option'] = $row['delivery_option'];
        }
    }else{
        $_SESSION['delivery_option'] = 'none';
    }
    
    
 
}



?>

<div class="checkout-page">
    <div class="left">
        <h1>Checkout</h1>
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
                }else{
                    header('Location: login');
                    exit();
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
        <input type="hidden" id="delivery_option_get" name="delivery_option_get" value="<?=$_SESSION['delivery_option']?>" />
        <div class="payment-section box">
            <h4>Delivery Option</h4>
            <div id='delivery' class="q cod for-delivery">
                <div class="pay-left">
                    <i class="fas fa-truck-loading"></i>
                    <label>For Delivery</label>
                    <p>Delivery Service</p>
                </div>
                <input type="radio" name="delivery_option" class="payment_method_input" value="delivery">
            </div>
            <div id='pickup' class="q paypal for-pickup">
                <div class="pay-left">
                    <i class="fas fa-person-carry"></i>
                    <label>For Pick-up</label>
                    <p>Pick-up your order at our store</p>
                </div>
                <input type="radio" name="delivery_option" class="payment_method_input" value="pickup">
            </div>
            
        </div>
        <hr>

        <div class="payment-section box">
            <h4>Payment Method</h4>

            <div id='cod' class="q cod ">
                <div class="pay-left">
                    <i class="fas fa-money-bill"></i>
                    <label>Cash on Delivery</label>
                    <p>Pay when you receive</p>
                </div>
                <input type="radio" name="payment_method" class="payment_method_input" value="cod">
            </div>
            <div id='gcash' class="q paypal">
                <div class="pay-left">
                    <img width="30" style="border-radius: 6px;" src="./images/gcash_logo.svg"></img>
                    <label>Gcash</label>
                    <p>Pay with Gcash e-wallet</p>
                </div>
                <input type="radio" name="payment_method" class="payment_method_input" value="gcash">
            </div>
            <div id='maya' class="q paypal">
                <div class="pay-left">
                    <img width="60" style="border-radius: 6px;" src="./images/maya_logo.svg"></img>
                    <label>Maya</label>
                    <p>Pay with Maya e-wallet</p>
                </div>
                <input type="radio" name="payment_method" class="payment_method_input" value="maya">
            </div>
            <div id='paypal' class="q paypal">
                <div class="pay-left">
                <i class="fab fa-paypal"></i>
                        <label>Paypal</label>
                    <p>Pay now with Paypal</p>
                </div>
                <input type="radio" name="payment_method" class="payment_method_input" value="paypal">
            </div>
            
        </div>

        <hr>
        <h3>Order Summary</h3>

        <?php
        if($_SESSION['discount'] === '1'){
            ?>
            <div class="subtotal">
                <p>Subtotal</p>
                <p >₱ <span id="subtotal_value"><?= number_format((float)$_SESSION['subtotal'], 2, '.', '') ?></span></p>
            </div>
            <hr>
            
            <div class="subtotal">
                <p>VAT Exempt Subtotal</p>
                <p >₱ <span id="vat_exempt_sale_value"><?= number_format((float)$_SESSION['vat-exempt-sale'], 2, '.', '') ?></span></p>
            </div>
            <?php
        }else{
            ?>
            <div class="subtotal">
                <p>Subtotal</p>
                <p >₱ <span id="subtotal_value"><?= number_format((float)$_SESSION['subtotal'], 2, '.', '') ?></span></p>
            </div>
        <?php
        }
        ?>
        <div class="subtotal shipping-fee">
            <p>Shipping Fee</p>
            <p >₱ <span id="shipping_value"><?= number_format((float)$_SESSION['shipping_cost'], 2, '.', '') ?></span></p>
        </div>
        <div class="subtotal">
            <p>Discount</p>
            <?php
                if($_SESSION['discount'] === '1'){
                ?>
                <p >- ₱ <span id="subtotal_value"><?= number_format((float)$_SESSION['discount-total'],2, '.', '') ?></span></p>
                <?php
                }else{
                ?>
                    <p >- ₱ <span id="subtotal_value"><?= number_format((float)0.00, 2, '.', '') ?></span></p>
                <?php
                }
                ?>
        </div>
        <?php
            if($_SESSION['discount'] === '1'){
                ?>
                    <span style="color:white;background:#1B262C;border-radius:10px;margin:0 10px;padding:3px 5px;text-align:center;">Discount Applied &#10004;</span>

                    <?php
            }else{
                ?>
                    <a class="px-2 text-lg-left" href="./discount-form?user_id=<?= $_SESSION['user_id'] ?>"> <u>Apply for Senior Citizen / PWD Discount <b>></b></u></a>
                <?php
            }
        ?>
        <hr>
        <div class="total">
            <h3>Total</h3>
            
            <input type="hidden" id="discount" value="<?= $_SESSION['discount'] ?>">
           
            <input type="hidden" id="subtotal" value="<?= $_SESSION['subtotal'] ?>">
            <input type="hidden" id="shippingfee" value="<?= $_SESSION['shipping_cost'] ?>">

            <p>₱ <span id="total_value"><?= number_format((float)$_SESSION['total'], 2, '.', '') ?></span></p>
        </div>
        <input type="hidden" id="user_id" value="<?= $_SESSION['user_id']; ?>">
        <button class="d-flex justify-content-center align-items-center place_order disabledButton">
            <div class="d-none place-order-spinner pr-2 spinner-border text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>    
            <div class="place-order-text px-2">Place Order</div>
        </button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<style>
    .q{
        border: 1px solid black;
        background-color: white;
    }
    .highlight {
        background: #B9D7EA ;
        border: 2px solid #3F72AF;
    }




</style>

<script>
    $(document).ready(function() {
        let refreshSummary = function(value) {

            const deliveryOption =  $('input[name="delivery_option"]:checked').val();
            const paymentMethod =   $('input[name="payment_method"]:checked').val();
            if(deliveryOption && paymentMethod){
                $('.place_order').removeClass('disabledButton');
                $('.place_order').addClass('activeButton');
            }
            
            $.ajax({
                url: 'includes/cart.inc.php',
                type: "POST",
                dataType: "json",
                data: {checkout_summary_update: true,
                        delivery_option: deliveryOption
                },

                success: function(result){
                    var discount_approval = result.discount_approval;
                    if(discount_approval == '1'){
                        $('#subtotal_value').text((Math.round(result.subtotal * 100) / 100).toFixed(2));
                        $('#shipping_value').text((Math.round(result.shipping_cost * 100) / 100).toFixed(2));
                        $('#total_value').text((Math.round(result.total * 100) / 100).toFixed(2));
                    }else{
                        $('#subtotal_value').text((Math.round(result.subtotal * 100) / 100).toFixed(2));
                        $('#shipping_value').text((Math.round(result.shipping_cost * 100) / 100).toFixed(2));
                        $('#total_value').text((Math.round(result.total * 100) / 100).toFixed(2));
                    }
                },
            });
        }
        

        $('input:radio').change(function(){
            var $this = $(this);
            $this.closest('.box').find('div.highlight').removeClass('highlight');
            $this.closest('.q').addClass('highlight');
            

        });

        $(".q").click(function(e) {
            e.stopPropagation();

            var $this = $(this);
            var $radio = $this.find('input');
            $radio.prop('checked', 'checked');
            $this.closest('.box').find('div.highlight').removeClass('highlight');
            $this.closest('.q').addClass('highlight');
            refreshSummary($radio.val());
        });

        var deliveryOptionGet = $('#delivery_option_get').val();
        if(deliveryOptionGet == 'delivery'){
            $('#delivery').trigger('click');
        }else if (deliveryOptionGet == 'pickup'){
            $('#pickup').trigger('click');
            
        }else{
            $('#delivery').trigger('click');

        }
        
        
        $('.place_order').click(function(){
            if($(this).hasClass('disabledButton')){
                alert('Please select payment method')
            }
            if($(this).hasClass('activeButton')){
                var user_id = $('#user_id').val();
                var payment_method = $('input[name="payment_method"]:checked').val();
                var delOption = $('input[name="delivery_option"]:checked').val();
                
                $('.place-order-spinner').removeClass('d-none');
                $('.place-order-spinner').addClass('d-inline-block');
                $('.place-order-text').text('Please Wait...');
                window.location = `place-order-post.php?method=${payment_method}&id=${user_id}&delivery_option=${delOption}`;
            }
        });
        
    });
</script>

<?php include_once 'includes/footer.php'; ?>
