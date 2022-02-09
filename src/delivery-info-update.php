<?php include_once 'includes/header.php'; ?>
<div class="delivery-info">
    <h3>Delivery Information</h3>
    <?php
        $user_id = $_GET['id'];
        $sql = "SELECT * FROM address WHERE user_id = '$user_id' LIMIT 1";
        $stmt = mysqli_query($conn, $sql);
        if(mysqli_num_rows($stmt)>0){
            foreach($stmt as $row){
                ?>
                <form action="./includes/redirect.php" method="post">
                    <div class="row">
                        <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">
                        <div class="col-md-12 mb-3">
                            <label for="address">House Number, Building and Street Name</label>
                            <input type="text" required class="form-control" name="address" value="<?= $row['address'] ?>" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="barangay">Barangay</label>
                            <input type="text" required class="form-control" name="barangay" value="<?= $row['barangay'] ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="city">City / Municipality</label>
                            <input type="text" required class="form-control" name="city" value="<?= $row['city'] ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="contact">Mobile Number</label>
                            <input type="text" required class="form-control" name="contact" placeholder="ex. 09123456789" value="<?= $row['contact'] ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="notes">Other Notes</label>
                            <textarea type="text" class="form-control" name="notes"  placeholder="Optional" ><?= $row['notes'] ?></textarea>
                        </div>
                        <input type="submit" name="update_address" value="Save">
                    </div>
                </form>
                <?php
            }
        }
    ?>
</div>
<?php include_once 'includes/footer.php'; ?>