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
                    <h4>Update Category<a href="category-view" class="btn btn-danger float-end">Back</a></h4>
                </div>
                <div class="card-body">
                    <?php 
                        if(isset($_GET['id'])){
                            $cat_id = $_GET['id'];
                            $sql = "SELECT * FROM categories WHERE category_id='$cat_id'";
                            $stmt = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($stmt)>0){
                                $row = mysqli_fetch_array($stmt);
                                ?>
                                <form action="includes/functions.php" method="post">
                                    <div class="row">
                                        <input type="hidden" name="id" value="<?= $row['category_id']?>">
                                        <div class="col-md-6 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" value="<?= $row['name'];?>" required class="form-control" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" name="slug" value="<?= $row['slug'];?>"  class="form-control" >
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" rows="4"  class="form-control" ><?= $row['description'];?></textarea>
                                        </div>
                                        <hr>
                                        <h5>SEO</h5>
                                        <div class="col-md-12 mb-3">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" max="191" value="<?= $row['meta_title'];?>" required class="form-control" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" rows="4"  required class="form-control" ><?= $row['meta_description'];?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="meta_keyword">Meta Keyword</label>
                                            <textarea name="meta_keyword" rows="4" required class="form-control" ><?= $row['meta_keyword'];?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <button class="btn btn-primary" type="submit" name="category_update" >Update Category</button>
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