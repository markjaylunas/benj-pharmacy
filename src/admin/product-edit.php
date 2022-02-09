<?php
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Update Product<a href="product-view" class="btn btn-danger float-end">Back</a></h4>
                </div>
                <div class="card-body">
                    <?php 
                        if(isset($_GET['id'])){
                            $pro_id = $_GET['id'];
                            $pro_sql = "SELECT * FROM products WHERE product_id='$pro_id' LIMIT 1";
                            $pro_stmt = mysqli_query($conn, $pro_sql);
                            if(mysqli_num_rows($pro_stmt)>0){
                                $pro_row = mysqli_fetch_array($pro_stmt);
                                ?>
                                <form action="includes/functions.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="<?= $pro_row['product_id'] ?>" name="product_id" class="form-control" >
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" value="<?= $pro_row['name'] ?>" name="name" required class="form-control" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id">Category List</label>
                                            <select name="category_id" required class="form-control">
                                            <option>--Select Category--</option>
                                            <?php 
                                            $sql = "SELECT * FROM categories;";
                                            // $sql = "SELECT * FROM categories WHERE status='0';";
                                            $stmt = mysqli_query($conn, $sql);
                                            if(mysqli_num_rows($stmt)>0){
                                                foreach($stmt as $row)
                                                {
                                                ?>
                                                if(){
                                                    <option <?= $row['category_id']==$pro_row['category_id'] ?'selected':'' ?> value="<?= $row['category_id']?>"><?= $row['name']?></option>
                                                }
                                                
                                                <?php
                                                }
                                            }else{
                                                ?>
                                                <option value="0">No Categories Found</option>
                                                <?php 
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="slug">Slug(URL)</label>
                                            <input type="text" value="<?= $pro_row['slug'] ?>" name="slug"  class="form-control" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" value="<?= $pro_row['price'] ?>" name="price" required class="form-control" >
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea id="summernotePEDes" name="description" rows="4" class="form-control" ><?= $pro_row['description'] ?></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="specification">Specification</label>
                                            <textarea id="summernotePESpe" name="specification" rows="4" required class="form-control" ><?= $pro_row['specification'] ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="image">Image</label>
                                            <input type="hidden" name="old_image" value="<?= $pro_row['image'] ?>">
                                            <input type="file" name="image"  class="form-control" >
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="status">Status</label>
                                            <input type="checkbox" name="status" <?= $pro_row['status'] == '1' ? 'checked':'' ?> width="70px" height="70px"  >
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="stock">Stock</label>
                                            <input type="number"  value="<?= $pro_row['stock'] ?>" name="stock" max="191" required class="form-control" >
                                        </div>
                                        <hr>
                                        <h5>SEO</h5>
                                        <div class="col-md-12 mb-3">
                                            <label for="meta_title">Meta Title</label> <br>
                                            <input type="text" name="meta_title" value="<?= $pro_row['meta_title'] ?>" max="191" required class="form-control" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" rows="4" required class="form-control" ><?= $pro_row['meta_description'] ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="meta_keyword">Meta Keyword</label>
                                            <textarea name="meta_keyword" rows="4" required class="form-control" ><?= $pro_row['meta_keyword'] ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <button class="btn btn-primary" type="submit" name="product_update" >Update Product</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                
                            }
                        }else{
                            ?>
                            <h4>No Record Found</h4>
                            <?php
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