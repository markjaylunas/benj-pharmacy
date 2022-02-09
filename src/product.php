<?php include_once 'includes/header.php'; 
include_once 'includes/dbh.inc.php'; 

if (isset($_GET['product'])){
    if(!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = false;
    }
    $product_slug = $_GET["product"];
    $product_sql = "SELECT * FROM products WHERE slug='$product_slug' LIMIT 1";
    $product_stmt = mysqli_query($conn, $product_sql);
    if(mysqli_num_rows($product_stmt)>0){
        $product_res = mysqli_fetch_array($product_stmt);
        ?>  
        <div class="product-page">
            <div class="left">
                <div class="product-image">
                    <img src="./uploads/products/<?= $product_res['image']; ?>" alt="<?= $product_res['name']; ?>">
                </div>
            </div>
            <div class="right">
                <h1 class="product-name"><?= $product_res['name']; ?></h1>
                <p class="product-price">₱ <?= $product_res['price']; ?></p>
                <form action="./includes/redirect.php" method="post">
                    <div class="quantity">
                        <p>Quantity</p>
                        <i class="far fa-minus-circle minus_one"></i>
                        <input type="number" name="product_quantity" value="1">
                        <i class="far fa-plus-circle add_one"></i>
                        <input type="hidden" name="product_id" value="<?= $product_res['product_id'];?>">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'];?>">
                    </div>
                    <button class="add-to-cart" type="submit" name="add_to_cart">Add to Cart</button>
                </form>
                <?php include('./message.php') ?>

                <div class="product-details">
                    <?= $product_res['specification']; ?>
                </div>
                <p class="product-description">
                    <?= $product_res['description']; ?>
                </p>
            </div>
        </div>
    <?php
    }else{
        ?>
        <div class="null">
            <h4>No Such Product</h4>
        </div>
        <?php
    }
}else{
    ?>
        <div class="null">
            <h4>No Such URL</h4>
        </div>
        <?php
}
?>
<?php include_once 'includes/footer.php'; ?>