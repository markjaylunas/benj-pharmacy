<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');

if(isset($_GET['id'])){
    $user_id = $_GET['id'];
}else{
    header('Location: discount-view');
    exit();
}
?>
<div class="container px-4">
    <h4 class="mt-4">Discount Member</h4>
    <a href="discount-view" class="btn btn-danger float-end">Back</a> 
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Discounts</li>
        <li class="breadcrumb-item active">Discount Members</li>
        <li class="breadcrumb-item active">Member View</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <?php 
                        $user_discount_sql = "SELECT u.*, d.* FROM users u, discounts d WHERE u.user_id=d.user_id AND u.user_id='$user_id'";
                        $user_discount_stmt = mysqli_query($conn, $user_discount_sql);
                        if(mysqli_num_rows($user_discount_stmt)>0){
                            $user_discount_res = mysqli_fetch_array($user_discount_stmt);
                        }
                        
                    
                    ?>  
                    <label for="status"><b>Approval Status: </b></label>
                    <form action="./includes/functions.php" method="POST">
                        <select class="status p-1 my-2 text-primary" onchange="submit()" name="approval" >
                            <option style="color:#FFC300" <?= $user_discount_res['approval']==0?'selected':'' ?> value="0">To Check</option>
                            <option style="color:#3EC70B" <?= $user_discount_res['approval']==1?'selected':'' ?> value="1">Approve</option>
                            <option style="color:#FF1818" <?= $user_discount_res['approval']==2?'selected':'' ?> value="2">Reject</option>
                            <input type="hidden" name="user_id" value="<?= $user_discount_res['user_id'] ?>" />
                            <input style="display: none;" type="sumbit" name="discount_status_update" />
                        </select>
                    </form> 
                    
                    
                    <p style="margin: 0;" class="mt-4"><b>Name:</b> <?= ucfirst($user_discount_res['first_name']).' '.ucfirst($user_discount_res['last_name'])?></p>
                    <p style="margin: 0;"><b>Email:</b> <?= $user_discount_res['email']?></p>
                    <p style="margin: 0;"><b>Date Created:</b> <?= date('M,d Y - D', strtotime($user_discount_res['created_at']))?></p>
                    <p style="margin: 0;"><b>Date Updated:</b> <?= date('M,d Y - D', strtotime($user_discount_res['updated_at']))?></p>
                    
                </div>
                <div class="card-body">
                    <table id="orderEditDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Image</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT * FROM discounts_files WHERE user_id='$user_id';";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        ?>
                                        
                                        <tr>
                                            <td><?=$row['filename_alt']?></td>
                                            <td><a href="<?= '../uploads/discounts/'.$row['filename'];?>" target="_blank" title="CLICK TO OPEN IN NEW TAB"><img height="100" src="<?= '../uploads/discounts/'.$row['filename'];?>" alt="<?= $row['filename_alt']; ?>"></a></td>
                                            <td><?= $row['created_at']; ?></td>
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