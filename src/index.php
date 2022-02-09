<?php include_once 'includes/header.php';
?>
        <main>
            <div class="banner">
                <div class="left">
                    <div class="upper">
                        <h1>Benj <br> Pharmacy</h1>
                        <p>Benj Pharmacy is now online! Conveniently order and access a wide range of products through our online store and delivery services.</p>
                    </div>
                    <div class="lower"><a href="./all-products" id="buyNow">Buy Now</a></div>
                </div>
                <div class="right"><img src="./images/landing.jpg" alt="landing image"></div>
            </div> 
            <div class="products">
                <section class="featured-products">
                    <h1>Featured Products</h1>
                    <div class="item-row">
                        <?php 
                            $pro_sql = "SELECT * FROM products p, pages_status s WHERE p.product_id = s.product_id AND s.featured = 1;";
                            $pro_stmt = mysqli_query($conn, $pro_sql);
                            if($pro_stmt){
                                foreach($pro_stmt as $pro_row){
                                    ?>
                                    <div class="item clickable ">
                                        <img class="item-img" src="./uploads/products/<?= $pro_row['image']?>" alt="<?= $pro_row['name']?>">
                                        <a href="product?product=<?= $pro_row['slug']?>" class="item-name"><?= $pro_row['name']?></a>
                                        <p class="item-price">₱<?= $pro_row['price']?></p>
                                    </div>
                                    <?php
                                }
                            }else{
                                ?>
                        
                                <li><a href="#">No Products Found</a></li>
                                <?php
                            }
                        ?>
                    </div>
                </section>
                <section class="featured-products">
                    <h1>Exclusive Deals</h1>
                    <div class="item-row">
                        <?php 
                            $pro_sql = "SELECT * FROM products p, pages_status s WHERE p.product_id = s.product_id AND s.exclusive = 1;";
                            $pro_stmt = mysqli_query($conn, $pro_sql);
                            if($pro_stmt){
                                foreach($pro_stmt as $pro_row){
                                    ?>
                                    <div class="item clickable ">
                                        <img class="item-img" src="./uploads/products/<?= $pro_row['image']?>" alt="<?= $pro_row['name']?>">
                                        <a href="product?product=<?= $pro_row['slug']?>" class="item-name"><?= $pro_row['name']?></a>
                                        <p class="item-price">₱<?= $pro_row['price']?></p>
                                    </div>
                                    <?php
                                }
                            }else{
                                ?>
                        
                                <li><a href="#">No Products Found</a></li>
                                <?php
                            }
                        ?>
                    </div>
                </section>
            </div>
        </main>
        
<?php include_once 'includes/footer.php'; ?>