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
}else{
    header('Location: login');
    exit();
}



?>

<div class="cart-page">
    <div class="orders">
    <?php
        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            
            $cart_sql = "SELECT p.*,c.* FROM products p, cart c WHERE p.product_id=c.product_id AND c.user_id ='$user_id';";
            $cart_stmt = mysqli_query($conn, $cart_sql);
            if(mysqli_num_rows($cart_stmt)>0){
                $_SESSION['cart_count'] = mysqli_num_rows($cart_stmt);
            }else{
                $_SESSION['cart_count'] = 0;
            }
        }
    ?>
        <h4>My Cart<span>(<?= $_SESSION['cart_count']?>)</span></h4>
        <div class="head">
            <div class="left">
                <input type="checkbox" class="cart_item_all_checkbox" name="selected_all" value="<?= isset($_SESSION['user_id'])?$_SESSION['user_id']:'' ?>">
                <label id="select_all_label" for="selected_all">Select All</label>
            </div>
            <div class="right">
                <form action="includes/redirect" method="post" >
                    <button type="submit"  class="select" value="<?= isset($_SESSION['user_id'])?$_SESSION['user_id']:'' ?>" id="delete" name="cart_all_item_delete" >
                        <i class="far fa-trash-alt"></i>
                        Delete
                    </button>
                </form>
            </div>
            <!-- <i class="fas fa-check-square"></i>
            <i class="far fa-trash-alt"></i>
            <i class="far fa-map-marker-alt"></i> -->
        </div>
        <?php 
            if(isset($_SESSION['user_id'])){
                $user_id = $_SESSION['user_id'];
                
                $cart_sql = "SELECT p.*,c.* FROM products p, cart c WHERE p.product_id=c.product_id AND c.user_id ='$user_id';";
                $cart_stmt = mysqli_query($conn, $cart_sql);
                if($cart_count = mysqli_num_rows($cart_stmt)>0){
                    foreach($cart_stmt as $cart_row){
                        $cart_id = $cart_row['cart_id'];
                        $user_id = $cart_row['user_id'];
                        ?>
                            <div class="order">
                                <input type="checkbox" class="cart_item_checkbox" <?= $cart_row['checked']==1?'checked':''; ?> name="selected" value="<?= $cart_row['cart_id'] ?>">
                                <img src="./uploads/products/<?= $cart_row['image'] ?>" alt="<?= $cart_row['name'] ?>">
                                <p class="product-name"><?= $cart_row['name'] ?></p>
                                <div class="product-price-summary">
                                    <p class="product-price">₱<?= $cart_row['price'] ?></p>
                                    <div class="quantity">
                                        <button class="qty_minus"><i class="far fa-minus-circle"></i></button>
                                        <input  class="qty_value" type="number" readonly id="<?= $cart_row['cart_id'] ?>" name="product-quantity" value="<?= $cart_row['quantity'] ?>">
                                        <button class="qty_plus"><i class="far fa-plus-circle"></i></button>
                                    </div>
                                </div>
                                <form action="includes/redirect" method="post" >
                                    <input type="hidden" name="user_id" value="<?= $cart_row['user_id'] ?>">
                                    <button type="submit" class="scroll_back" value="<?= $cart_row['cart_id'];?>" name="cart_item_delete" >
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
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
    <div class="order-summary">
        
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
        <hr>
        
        <div class="total">
            <h3>Total</h3>
            <p>₱ <span id="total_value"><?= number_format((float)($_SESSION['subtotal']), 2, '.', '') ?></span></p>
        </div>
        <button id="checkout" class="<?= $address?'have_address':'no_address' ?>">Checkout</button>
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
                data: {cart_summary_update: true},
                success: function(result){
                    $('#subtotal_value').text((Math.round(result.subtotal * 100) / 100).toFixed(2));
                    $('#shipping_value').text((Math.round(result.shipping_cost * 100) / 100).toFixed(2));
                    $('#total_value').text((Math.round(result.total * 100) / 100).toFixed(2));
                },
            });
        }
        $(".qty_plus").click(function() {
            if($(this).next().val()!=1){
                $(this).prev().val(parseInt($(this).prev().val())+1);
                $(".qty_value").change();
            }
        });
        $(".qty_minus").click(function() {
            if($(this).next().val()!=1){
                $(this).next().val(parseInt($(this).next().val())-1);
                $(".qty_value").change();
            }
        });
        $(".qty_value").change(function(event) {
            var cart_id = event.target.id;
            var qty = parseInt($(this).val());
            $.ajax({
                url: 'includes/cart.inc.php',
                type: "POST",
                data: {quantity_update: qty,
                        cart_id: cart_id
                },
                success: function(result){
                    console.log(result);
                    refreshSummary();
                },
            });
        });
        $(".cart_item_checkbox").click(function(event) {
            var cart_id = $(this).attr('value');
            var cart_id_checked = this.checked?1:0;
            
            $.ajax({
                url: 'includes/cart.inc.php',
                type: "POST",
                data: {cart_checked: cart_id_checked,
                        cart_id: cart_id},
                success: function(result){
                    refreshSummary();
                },
            });
        })
        function toggleSelectAll(status){
            $(".cart_item_checkbox").prop('checked', !status);
            $(".cart_item_checkbox").click();
        }
        $('#select_all_label').click(function(event) {
            var status = $(this).prev().is(":checked")?false:true;
            $('.cart_item_all_checkbox').prop('checked', status);
            toggleSelectAll(status);
        });
        $('.cart_item_all_checkbox').click(function(event) {
            var status = $(this).is(":checked")?true:false;
            toggleSelectAll(status);
        });

        $('#checkout').click(function(){
            if($(this).hasClass('no_address')){
                alert('Please add new address');
            }
            if($(this).hasClass('have_address')){
                $.ajax({
                    url: 'includes/cart.inc.php',
                    type: "POST",
                    dataType: "json",
                    data: {checkout: true},
                    success: function(result){
                        if(result.result == 'true'){
                            window.location = 'checkout';
                        }else{
                            alert('Please add and select products before checkout');
                        }
    
                        
                    },
                });
            }
        });
        
    });
</script>

<?php include_once 'includes/footer.php'; ?>
