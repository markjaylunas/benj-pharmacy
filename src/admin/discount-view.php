<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h4 class="mt-4">Discount Members</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Discount</li>
        <li class="breadcrumb-item active">Discount Members</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Discount Members</h4>
                    
                </div>
                <div class="card-body">
                    <table id="orderDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User ID</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT d.*, u.user_id, u.first_name, u.last_name, u.email FROM discounts d, users u WHERE u.user_id=d.user_id ORDER BY d.updated_at DESC;";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        
                                        if($row['approval']==='0'){
                                            $approval_color = 'color: #FFC300;';
                                            $approval = 'To Check';
                                        }elseif($row['approval']==='1'){
                                            $approval_color = 'color: #3EC70B;';
                                            $approval = 'Approved';
                                        }elseif($row['approval']==='2'){
                                            $approval_color = 'color: #FF1818;';
                                            $approval = 'Rejected';
                                        }

                                        ?>
                                        <tr>
                                            <td style="<?= $approval_color ?>"><u><?= $approval ?></u></td>
                                            <td><?=$row['first_name'].' '.$row['last_name']?></td>
                                            <td><?=$row['email']?></td>
                                            <td><?= $row['user_id']; ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td><?= $row['updated_at']; ?></td>
                                            <td><a href="discount-edit?id=<?= $row['user_id']; ?>" class="btn btn-primary">View</a></td>
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