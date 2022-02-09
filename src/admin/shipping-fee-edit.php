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
                    <h4>Update Shipping Fee</h4>
                </div>
                <div class="card-body">
                    <?php 
                        $shipping_sql = "SELECT * FROM shipping_fee WHERE shipping_id='1' LIMIT 1";
                        $shipping_stmt = mysqli_query($conn, $shipping_sql);
                        if(mysqli_num_rows($shipping_stmt)>0){
                            $shipping_row = mysqli_fetch_array($shipping_stmt);
                            ?>
                            <form action="includes/functions.php" method="post">
                                
                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" value="<?= $shipping_row['cost'] ?>" name="shipping_fee" required class="form-control" >
                                    </div>
                                    <div class="col-md-6 mt-4 mb-3">
                                        <button class="btn btn-primary" type="submit" name="shipping_fee_update" >Update Shipping Fee</button>
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