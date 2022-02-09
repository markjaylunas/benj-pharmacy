<?php 
// session_start();
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container px-4">
    <h4 class="mt-4">Products</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Products</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>All Products<a href="product-add" class="btn btn-primary float-end">Add Product</a></h4>
                    
                </div>
                <div class="card-body">
                    <table id="productsDatatable" class="display">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT p.*, c.name AS cname FROM products p, categories c WHERE p.category_id=c.category_id;";
                                $query_run = mysqli_query($conn, $query);
                                if(mysqli_num_rows($query_run)>0){
                                    foreach($query_run as $row){
                                        ?>
                                        <tr>
                                            <td><?= $row['product_id']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= $row['cname']; ?></td>
                                            <td>
                                                <img width="80px" src="../uploads/products/<?= $row['image']; ?>" alt="<?= $row['name']; ?>">
                                            </td>
                                            <td><?= $row['stock']; ?></td>
                                            <td><?= $row['status']=='1'? 'Hidden':'Visible'; ?></td>
                                            <td><a href="product-edit?id=<?= $row['product_id']; ?>" class="btn btn-primary">Edit</a></td>
                                            <td>
                                                <form action="includes/functions.php" method="post" >
                                                    <input type="hidden" name="image_delete" value="<?= $row['image']?>">
                                                    <button type="submit" class="btn btn-danger" value="<?= $row['product_id'];?>" name="product_delete" onclick="return confirm('Are you sure you want to delete this Product?');" >Delete</button>
                                                </form>
                                            </td>
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