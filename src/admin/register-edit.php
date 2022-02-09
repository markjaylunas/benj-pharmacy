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
                    <h4>Edit User</h4>
                </div>
                <div class="card-body">
                    <?php 
                        if(isset($_GET['id'])){
                            $user_id = $_GET['id'];
                            $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
                            $stmt = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($stmt)>0){
                                $row = mysqli_fetch_array($stmt);
                                ?>
                                    <form action="includes/functions.php" method="post">
                                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fname">First Name</label>
                                                <input type="text" name="fname" value="<?= $row['first_name']; ?>" class="form-control" >
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="lname">Last Name</label>
                                                <input type="text" name="lname" value="<?= $row['last_name'] ?>"; class="form-control" >
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" value="<?= $row['email'] ?>"; class="form-control" >
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <button class="btn btn-primary" type="submit" name="user_update" >Update User</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php
                            }else{
                                ?>
                                <h4>No Record Found</h4>
                                <?php
                            }
                        }
                    ?>
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