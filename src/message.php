<?php 
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>

<div class="alert alert-success alert-dismissible fade show"  style="width: 300px;" role="alert">
    <?php echo $message; ?>
</div>
<?php
    unset($_SESSION['message']);
}
?>