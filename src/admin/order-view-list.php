<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');

if(isset($_GET['id'])){
    $order_id = $_GET['id'];
}else{
    header('Location: order-view');
    exit();
}
?>
<div class="container px-4">
    <h4 class="mt-4">Order</h4>
    <a href="order-view" class="btn btn-danger float-end">Back</a> 
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Orders</li>
        <li class="breadcrumb-item active">Order</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <?php 
                        $user_order_sql = "SELECT op.*, u.*, o.*, o.created_at AS oCreatedAt FROM orders_products op, users u, orders o WHERE op.order_id='$order_id' AND u.user_id=op.user_id AND o.order_id=op.order_id";
                        $user_order_stmt = mysqli_query($conn, $user_order_sql);
                        if(mysqli_num_rows($user_order_stmt)>0){
                            $user_order_res = mysqli_fetch_array($user_order_stmt);
                        }
                        
                    ?>
                    
                    <form action="./includes/functions.php" method="POST">
                        <select class="status p-1 my-2" onchange="submit()" name="status" >
                            <option <?= $user_order_res['delivery_status']==0?'selected':'' ?> value="0">To Check</option>
                            <option <?= $user_order_res['delivery_status']==1?'selected':'' ?> value="1">Packed</option>
                            <option <?= $user_order_res['delivery_status']==2?'selected':'' ?> value="2">Out for Delivery</option>
                            <option <?= $user_order_res['delivery_status']==3?'selected':'' ?> value="3">Delivered</option>
                            <input type="hidden" name="order_id" value="<?= $user_order_res['order_id'] ?>" />
                            <input style="display: none;" type="sumbit" name="order_status_update" />
                        </select>
                    </form>
                    <h5>Order ID: <?= $user_order_res['order_id']?></h5>
                    <p style="margin: 0;">Customer: <?= $user_order_res['first_name'].' '.$user_order_res['last_name']?></p>
                    <p style="margin: 0;">Shipping Fee: ₱<?= $user_order_res['shipping_fee']?></p>
                    <p style="margin: 0;">Total: ₱<?= $user_order_res['total']?></p>
                    <p style="margin: 0;">Date: <?= date('M,d Y - D', strtotime($user_order_res['oCreatedAt']))?></p>
                    
                </div>
                <div class="card-body">
                    <table id="orderEditDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT * FROM orders_products WHERE order_id='$order_id'";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        ?>
                                        <tr>
                                        <td><?= $row['product_id']; ?></td>
                                            <td><?=$row['name']?></td>
                                            <td><?= $row['quantity']; ?></td>
                                            <td>₱<?= $row['price']; ?></td>
                                            <td>₱<?= number_format((float)(number_format((float)$row['price'], 2, '.', '')*number_format((float)$row['quantity'], 2, '.', '')), 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="6">No Orders Found</td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
ob_start();
include('./includes/footer.php');
include('./includes/scripts.php');
?>