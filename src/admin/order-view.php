<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h4 class="mt-4">Orders</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Orders</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Orders</h4>
                    
                </div>
                <div class="card-body">
                    <table id="orderDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Name - ID</th>
                                <th>Email</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Payment Method</th>
                                <th>Delivery Status</th>
                                <th>Created at</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT o.*, u.first_name, u.last_name, u.email FROM orders o, users u WHERE u.user_id=o.user_id ORDER BY o.created_at ASC;";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        if($row['delivery_status']==0){
                                            $delivery_status_color = 'color: #FF1700;';
                                            $delivery_status = 'To Check';
                                        }elseif($row['delivery_status']==1){
                                            $delivery_status_color = 'color: #3F72AF;';
                                            $delivery_status = 'Packed';
                                        }elseif($row['delivery_status']==2){
                                            $delivery_status_color = 'color: #FFE162;';
                                            $delivery_status = 'Out for Delivery';
                                        }elseif($row['delivery_status']==3){
                                            $delivery_status_color = 'color: #1B262C;';
                                            $delivery_status = 'Delivered';
                                        }

                                        if( $row['paid']==0){
                                            $paid_status_color = 'color: #FF1700';
                                        }elseif($row['paid']==1){
                                            $paid_status_color = 'color: #06FF00';
                                        }
                                        ?>
                                        <tr>
                                        <td><?= $row['order_id']; ?></td>
                                            <td><?=$row['first_name'].' '.$row['last_name']?></td>
                                            <td><?=$row['email']?></td>
                                            <td>₱<span><?= $row['total']; ?></span></td>
                                            <td style="<?= $paid_status_color ?>"><?= $row['paid']==0?'Unpaid':'Paid'; ?></td>
                                            
                                            <td><?= $row['payment_method']; ?></td>
                                            <td style="<?= $delivery_status_color ?>"><?= $delivery_status ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td><a href="order-view-list?id=<?= $row['order_id']; ?>" class="btn btn-primary">View</a></td>
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