<?php include_once 'includes/header.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $order_id = $_GET['id'];
    
    $summary_sql = "SELECT p.price, op.quantity FROM products p, orders_products op WHERE op.order_id ='$order_id' AND op.user_id='$user_id' AND op.product_id=p.product_id";

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
    $_SESSION['total'] = $_SESSION['subtotal'] + $_SESSION['shipping_cost'];
}



?>

<div class="place-order-page">
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
    </div>
        <div class="orders">
        <?php 
            if(isset($_SESSION['user_id'])){
                $user_id = $_SESSION['user_id'];
                $order_id = $_GET['id'];
                
                $ordered_sql = "SELECT p.*, op.* FROM products p, orders_products op WHERE op.order_id ='$order_id' AND op.user_id='$user_id' AND op.product_id=p.product_id;";
                $ordered_stmt = mysqli_query($conn, $ordered_sql);
                
                if(mysqli_num_rows($ordered_stmt)>0){
                    foreach($ordered_stmt as $ordered_row){
                        $user_id = $ordered_row['user_id'];
                        ?>
                            <div class="order">
                                <img src="./uploads/products/<?= $ordered_row['image'] ?>" alt="<?= $ordered_row['name'] ?>">
                                <p class="product-name"><?= $ordered_row['name'] ?></p>
                                <div class="product-price-summary">
                                    <p class="product-price">₱<?= $ordered_row['price'] ?></p>
                                    <div class="quantity">
                                        <p class="checkout_p">Quantity: <span><?= $ordered_row['quantity'] ?></span></p>
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
        <div class="total">
            <p>Total: <span>₱ <?= number_format((float)$_SESSION['total'], 2, '.', '') ?></span> </p>
        </div>
        </div>
        <a href="index"> <span class="fas fa-chevron-left"></span> Go to Home Page</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


<?php include_once 'includes/footer.php'; ?>
