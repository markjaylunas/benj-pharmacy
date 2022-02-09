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
                    <h4>Update About Us Page</h4>
                </div>
                <div class="card-body">
                    <?php 
                        $about_sql = "SELECT * FROM about_us WHERE about_id='1' LIMIT 1";
                        $about_stmt = mysqli_query($conn, $about_sql);
                        if(mysqli_num_rows($about_stmt)>0){
                            $about_row = mysqli_fetch_array($about_stmt);
                            ?>
                            <form action="includes/functions.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="title">Title</label> <br>
                                        <input type="text" name="title" value="<?= $about_row['title'] ?>" class="form-control" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea id="summernotePEDes" name="description" rows="4" class="form-control" ><?= $about_row['description'] ?></textarea>
                                        </div>
                                    <div class="col-md-12 mt-4 mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="image">Image</label>
                                        <input type="hidden" name="old_image" value="<?= $about_row['image'] ?>">
                                        <input type="file" name="image"  class="form-control" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <img src="../uploads/about_us/<?= $about_row['image']?>" class="rounded img-fluid" width="400" alt="">
                                    </div>
                                        <button class="btn btn-primary" type="submit" name="about_update" >Update About Page</button>
                                    </div>
                                </div>
                            </form>
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