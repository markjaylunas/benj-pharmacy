<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h4 class="mt-4">Cancelled Orders</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Cancelled Orders</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Cancelled Orders</h4>
                    
                </div>
                <div class="card-body">
                    <table id="orderDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Order ID</th>
                                <th>Name - ID</th>
                                <th>Email</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Payment Method</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT o.*, u.first_name, u.last_name, u.email FROM orders o, users u WHERE u.user_id=o.user_id AND NOT o.cancelled='0' ORDER BY o.updated_at DESC;";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        // cancellation table
                                        $user_id_row = $row['user_id'];
                                        $order_id_row = $row['order_id'];
                                        $cancellation_res;
                                        $cancellation_sql = "SELECT * FROM cancellation WHERE order_id='$order_id_row' LIMIT 1";
                                        $cancellation_stmt = mysqli_query($conn, $cancellation_sql);
                                        if(mysqli_num_rows($cancellation_stmt)>0){
                                            foreach($cancellation_stmt as $order_row){
                                                $cancellation_res = $order_row;
                                            }
                                        }
                                        if($cancellation_res['status']==='0'){
                                            $delivery_status_color = 'color: #7F8487;';
                                            $delivery_status = 'Cancellation Processing';
                                        }elseif($cancellation_res['status']==='1'){
                                            $delivery_status_color = 'color: #3EC70B;';
                                            $delivery_status = 'Cancelled';
                                        }elseif($cancellation_res['status']==='2'){
                                            $delivery_status_color = 'color: #F32424;';
                                            $delivery_status = 'Cancellation Declined';
                                        }

                                        if( $row['paid']==0){
                                            $paid_status_color = 'color: #FF1700';
                                        }elseif($row['paid']==1){
                                            $paid_status_color = 'color: #06FF00';
                                        }
                                        ?>
                                        <tr>
                                            <td style="<?= $delivery_status_color ?>"><?= $delivery_status ?></td>
                                            <td><?= $row['order_id']; ?></td>
                                            <td><?=$row['first_name'].' '.$row['last_name']?></td>
                                            <td><?=$row['email']?></td>
                                            <td>₱<span><?= $row['total']; ?></span></td>
                                            <td style="<?= $paid_status_color ?>"><?= $row['paid']==0?'Unpaid':'Paid'; ?></td>
                                            
                                            <td><?= $row['payment_method']; ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td><?= $row['updated_at']; ?></td>
                                            <td><a href="order-cancelled-view?id=<?= $row['order_id']; ?>" class="btn btn-primary">View</a></td>
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