<?php 
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Total Registered Users
                    <?php 
                        $users_sql = "SELECT * FROM users;";
                        $users_stmt = mysqli_query($conn, $users_sql);
                        if($users_count = mysqli_num_rows($users_stmt)){
                            ?>
                            <h4 class="mb-0"><?= $users_count; ?></h4>
                            <?php
                        }else{
                            ?>
                            <h4 class="mb-0">No Data</h4>
                            <?php
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="register-view">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Products
                <?php 
                        $product_sql = "SELECT * FROM orders;";
                        $product_stmt = mysqli_query($conn, $product_sql);
                        if($product_count = mysqli_num_rows($product_stmt)){
                            ?>
                            <h4 class="mb-0"><?= $product_count; ?></h4>
                            <?php
                        }else{
                            ?>
                            <h4 class="mb-0">No Data</h4>
                            <?php
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="product-view">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Total Categories
                <?php 
                        $category_sql = "SELECT * FROM categories;";
                        $category_stmt = mysqli_query($conn, $category_sql);
                        if($category_count = mysqli_num_rows($category_stmt)){
                            ?>
                            <h4 class="mb-0"><?= $category_count; ?></h4>
                            <?php
                        }else{
                            ?>
                            <h4 class="mb-0">No Data</h4>
                            <?php
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="category-view">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Total Orders
                <?php 
                        $orders_sql = "SELECT * FROM orders;";
                        $orders_stmt = mysqli_query($conn, $orders_sql);
                        if($orders_count = mysqli_num_rows($orders_stmt)){
                            ?>
                            <h4 class="mb-0"><?= $orders_count; ?></h4>
                            <?php
                        }else{
                            ?>
                            <h4 class="mb-0">No Data</h4>
                            <?php
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-left">
                    <a class="small text-white stretched-link" href="order-view">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
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