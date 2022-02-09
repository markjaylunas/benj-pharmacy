<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h4 class="mt-4">Users</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Registered User <a href="index" class="btn btn-warning float-end">Back</a></h4>
                    
                </div>
                <div class="card-body">
                    <table id="usersDatatable" class="display">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT * FROM users";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        ?>
                                        <tr>
                                            <td><?= $row['user_id']; ?></td>
                                            <td><?= $row['first_name']; ?></td>
                                            <td><?= $row['last_name']; ?></td>
                                            <td><?= $row['email']; ?></td>
                                            <td>
                                                <form action="includes/functions.php" method="post" >
                                                    <button type="submit" class="btn btn-danger" value="<?= $row['user_id'];?>" name="user_delete" onclick="return confirm('Are you sure you want to delete this user?');" >Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="6">No Record Found</td>
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