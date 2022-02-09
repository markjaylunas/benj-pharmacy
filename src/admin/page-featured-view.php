<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h4 class="mt-4">Featured Products</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Pages</li>
        <li class="breadcrumb-item active">Homepage</li>
        <li class="breadcrumb-item active">Featured Products</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Featured Products</h4>
                    
                </div>
                <div class="card-body">
                    <table id="featuredDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>On Featured Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT p.*, s.featured AS featured FROM products p, pages_status s WHERE p.product_id = s.product_id";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        ?>
                                        <tr>
                                            <td><?= $row['product_id']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td>
                                                <img width="80px" src="../uploads/products/<?= $row['image']; ?>" alt="<?= $row['name']; ?>">
                                            </td>
                                            <td><a href="page-featured-toggle.php?id=<?= $row['product_id']; ?>" class="btn btn-<?= $row['featured']=='0'? 'primary':'danger';?>"><?= $row['featured']=='0'? 'Add':'Remove';?></a></td>
                            
                                        </tr>
                                        <?php
                                    }
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="6">No Product Found</td>
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