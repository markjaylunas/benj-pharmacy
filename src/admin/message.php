<?php 
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>
<div class="alert alert-success  m-3 alert-dismissible fade show" role="alert">
    <?php echo $message; ?>
</div>
<?php
    unset($_SESSION['message']);
}
?>