<?php include_once 'includes/header.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header('Location: index');
    exit();
}

?>
<div class="account-page">
    <div class="left">
        <div class="card">
            <div class="card-header">
                <h4>My Account</h4>
            </div>
            <?php
                $account_sql = "SELECT * FROM users WHERE user_id ='$user_id' LIMIT 1";
                $account_stmt = mysqli_query($conn, $account_sql);
                if(mysqli_num_rows($account_stmt)>0){
                    $account_res = mysqli_fetch_array($account_stmt);
                }
            ?>
            <div class="card-body">
                <div class="row">
                    <label for="email">Email: <span><?= $account_res['email']?></span></label>
                    <label for="email">First Name: <span><?= $account_res['first_name']?></span></label>
                    <label for="email">Last Name: <span><?= $account_res['last_name']?></span></label>
                </div>
            </div>
        </div>
        <?php
            $address_sql = "SELECT * FROM address WHERE user_id ='$user_id' LIMIT 1";
            $address_stmt = mysqli_query($conn, $address_sql);
            if(mysqli_num_rows($address_stmt)>0){
                $address_res = mysqli_fetch_array($address_stmt);
                $have_address = true;
            }else{
                $have_address = false;
            }
        if($have_address){
        ?>
        <div class="card">
            <div class="card-header">
                <h4>Delivery Info</h4>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <label for="email">Address: <span><?= $address_res['address']?></span></label>
                    <label for="email">City: <span><?= $address_res['city']?></span></label>
                    <label for="email">Barangay: <span><?= $address_res['barangay']?></span></label>
                    <form action="./delivery-info-update?id=<?= $_SESSION['user_id']; ?>" method="post">
                        <button type="submit" class="btn btn-primary btn-sm my-3" name="btn_address">Edit Address</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        }else{
        ?>
        <div class="card">
            <div class="card-header">
                <h4>Delivery Info</h4>
            </div>
            <div class="card-body">
                <p>No Address Yet</p>
                <form action="./delivery-info?id=<?= $_SESSION['user_id']; ?>" method="post">
                    <button type="submit" class="btn btn-primary btn-sm my-3" name="btn_address">Add Address</button>
                </form>
            </div>
        </div>
        <?php
        }
        ?>
        
        
    </div>
    <div class="right">
        <h4>Order History</h4>
        <div class="orders">
        <?php 
            if(isset($_SESSION['user_id'])){
                $user_id = $_SESSION['user_id'];
                
                $order_sql = "SELECT * FROM orders WHERE user_id='$user_id'";
                $order_stmt = mysqli_query($conn, $order_sql);
                if(mysqli_num_rows($order_stmt)>0){
                    foreach($order_stmt as $order_row){
                        if($order_row['payment_method']=='cod'){
                            $method = 'Cash on Delivery';
                        }elseif($order_row['payment_method']=='gcash'){
                            $method = 'Paid with GCash';
                        }else{
                            $method = '';
                        }

                        if($order_row['delivery_status']==0){
                            $delivery_status_color = 'color: #FF1700;';
                            $delivery_status = 'To Check';
                        }elseif($order_row['delivery_status']==1){
                            $delivery_status_color = 'color: #3F72AF;';
                            $delivery_status = 'Packed';
                        }elseif($order_row['delivery_status']==2){
                            $delivery_status_color = 'color: #FFE162;';
                            $delivery_status = 'Out for Delivery';
                        }elseif($order_row['delivery_status']==3){
                            $delivery_status_color = 'color: #1B262C;';
                            $delivery_status = 'Delivered';
                        }


                        ?>
                            <div class="orders">
                                <div class="order">
                                    <div class="order-left">
                                        <p class="order_id">Order ID: <span><?= $order_row['order_id']?></span></p>
                                        <p class="created_at">Date: <span><?= date('M,d Y - D', strtotime($order_row['created_at'])) ?></span></p>
                                    </div>
                                    <div class="order-middle">
                                        <p><span><?= $method ?></span></p>
                                        <p>Total: <span>₱<?= $order_row['total'] ?></span></p>
                                    </div>
                                    <div class="order-right">
                                        <p>Status: <span><?= $delivery_status ?></span></p>
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
</div>
<?php include_once 'includes/footer.php'; ?>
