<?php include_once 'includes/header.php'; ?>
<div class="delivery-info">
    <h3>Delivery Information</h3>
    <form action="./includes/redirect.php" method="post">
        <div class="row">
            <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>">
            <div class="col-md-12 mb-3">
                <label for="address">House Number, Building and Street Name</label>
                <input type="text" required class="form-control" name="address" class="form-control">
            </div>
            <div class="col-md-12 mb-3">
                <label for="barangay">Barangay</label>
                <input type="text" required class="form-control" name="barangay">
            </div>
            <div class="col-md-12 mb-3">
                <label for="city">City / Municipality</label>
                <input type="text" required class="form-control" name="city">
            </div>
            <div class="col-md-12 mb-3">
                <label for="contact">Mobile Number</label>
                <input type="text" required class="form-control" name="contact" placeholder="ex. 09123456789">
            </div>
            <div class="col-md-12 mb-3">
                <label for="notes">Other Notes</label>
                <input type="text" class="form-control" name="notes"  placeholder="Optional">
            </div>
            <input type="submit" name="new_address" value="Save">
        </div>
    </form>
</div>



    <?php include_once 'includes/footer.php'; ?>