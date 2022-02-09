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
                    <h4>Add Product<a href="product-view" class="btn btn-danger float-end">Back</a></h4>
                </div>
                <div class="card-body">
                    <form action="includes/functions.php" method="post" enctype="multipart/form-data">
                        <div class="row">

                                <label for="name">Name</label>
                                <input type="text" name="name" required class="form-control" >
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
                                    <option value="<?= $row['category_id']?>"><?= $row['name']?></option>
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
                                <input type="text" name="slug"  placeholder="Optional" class="form-control" >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price">Price</label>
                                <input type="text" name="price" required class="form-control" >
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea id="summernotePADes" name="description" rows="4"  class="form-control" ></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="specification">Specification</label>
                                <textarea id="summernotePASpe" name="specification" rows="4" required class="form-control" ></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" required class="form-control" >
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status">Status</label><br>
                                <input type="checkbox" name="status" width="70px" height="70px"  >
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="stock">Stock</label>
                                <input type="number"  name="stock" max="191" required class="form-control" >
                            </div>
                            
                            <hr>
                            <h5>SEO</h5>
                            <div class="col-md-12 mb-3">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" name="meta_title" max="191" required class="form-control" >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meta_description">Meta Description</label>
                                <textarea name="meta_description" rows="4" required class="form-control" ></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meta_keyword">Meta Keyword</label>
                                <textarea name="meta_keyword" rows="4" required class="form-control" ></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-primary" type="submit" name="product_add" >Add Product</button>
                            </div>
                        </div>
                    </form>
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