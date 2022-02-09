<?php include_once 'includes/header.php';
$results_per_page = 4;


?>
<div class="all-products-page">
    <div class="category">
        <h1>Categories</h1>
        <ul>
            <?php 
                $cat_sql = "SELECT * FROM categories;";
                $cat_stmt = mysqli_query($conn, $cat_sql);
                if($cat_stmt){
                    foreach($cat_stmt as $cat_row){
                        ?>
                        <li><a href="?category=<?= $cat_row['slug']?>"><?= $cat_row['name']?></a></li>
                        <?php
                    }
                }else{
                    ?>
            
                    <li><a href="#">No Categories</a></li>
                    <?php
                }
            ?>
        </ul>
    </div>    
    <div class="all-products-container">
        <div class="head">
            <?php
                if(isset($_GET['search'])){
                    $head_title = 'Searched for: '.$_GET['search'];
                }elseif(isset($_GET['category'])){
                    $slug = $_GET['category'];
                    $head_sql = "SELECT * FROM categories WHERE slug='$slug' LIMIT 1";
                    $head_stmt = mysqli_query($conn, $head_sql);
                    if($head_stmt){
                        $head_row = mysqli_fetch_array($head_stmt);
                        $head_title = $head_row['name'];
                    }
                }else{
                    $head_title = 'All Products';
                }
            ?>
            <h1><?= $head_title ?></h1>
            

        <?php 
            if(isset($_GET['search'])){
                $search = $_GET['search'];
                ?>
                <div class="dropdown">
                
                <form action="" method="GET">
                    <select class="category-dropdown" onchange="submit()" name="category" >
                        <option <?= isset($_GET["category"])?($_GET['category']== "category"?'selected':''):'' ?> value="category">Category</option>
                <?php 
                    $cat_sql = "SELECT * FROM categories;";
                    $cat_stmt = mysqli_query($conn, $cat_sql);
                    if($cat_stmt){
                        foreach($cat_stmt as $cat_row){
                            ?>
                            <option <?= isset($_GET["category"])?($_GET['category']== $cat_row['slug']?'selected':''):'' ?> value="<?= $cat_row['slug']?>"><?= $cat_row['name']?></option>
                            <?php
                        }
                    }else{
                        ?>
                        <option value="category">No Categories</option>
                        <?php
                    }
                ?>
                    </select>
                </form>
                <form action="" method="GET">
                    <select class="sort" onchange="submit()" name="sort" >
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "sort"?'selected':''):'' ?> value="sort">Sort</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "name ASC"?'selected':''):'' ?> value="name ASC">A-Z</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "name DESC"?'selected':''):'' ?> value="name DESC">Z-A</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "price DESC"?'selected':''):'' ?> value="price DESC">Price: High to Low</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "price ASC"?'selected':''):'' ?> value="price ASC">Price: Low to High</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "created_at ASC"?'selected':''):'' ?> value="created_at ASC">Newest to Oldest</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "created_at DESC"?'selected':''):'' ?> value="created_at DESC">Oldest to Newest</option>
                        <input type="hidden" name="search" value="<?= $search ?>">

                    </select>
                </form>
                </div>
                </div>
                <div class="items">
                    <div class="upper-items">
                    <?php
                        if(!isset($_GET['page'])){
                            $page=1;
                        }else{
                            $page = (int)$_GET['page'];
                        }
                        // page variables
                        $res_per_page = 40;
                        $num_pro_sql = "SELECT p.* FROM products p, categories c WHERE p.status='0' AND p.category_id=c.category_id AND CONCAT(p.name,c.name) LIKE '%$search%'";
                        $num_pro_stmt = mysqli_query($conn, $num_pro_sql);
                        $num_res = mysqli_num_rows($num_pro_stmt);
                        $number_of_pages = ceil($num_res/$res_per_page);
                        $page_limit = ($page-1)*$res_per_page;

                        // get limited products
                        $pro_sql = "SELECT p.* FROM products p, categories c WHERE p.status='0' AND p.category_id=c.category_id AND CONCAT(p.name,c.name) LIKE '%$search%'";
                        $pro_limit_sql = " LIMIT $page_limit, $res_per_page";
                        if(isset($_GET['sort'])){
                            $concat_sql = ' ORDER BY '.$_GET['sort'];
                            if($_GET['sort'] == 'sort'){
                                $concat_sql = '';
                            }
                            $pro_sql = $pro_sql.$concat_sql.$pro_limit_sql;
                        }else{
                            $pro_sql = $pro_sql.$pro_limit_sql;
                        }

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
                                <h4 class="text-danger">No Product Available</h4>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="lower-pagination">
                        <nav aria-label="...">
                        <ul class="pagination pagination-sm">
                            <?php
                            $curr_page = $page;
                            $nav_left_status = '';
                            $nav_right_status = '';
                            if($curr_page==1){
                                $nav_left_status = 'disabled';
                            }
                            if($curr_page==$number_of_pages){
                                $nav_right_status = 'disabled';
                            }

                            // if sort exist
                            $sort_page = '';
                            if(isset($_GET['sort'])){
                                $sort_page = '&sort='.$_GET['sort'];
                            }
                            // if search exist
                            $search_page = '';
                            if(isset($_GET['search'])){
                                $search_page = '&search='.$_GET['search'];
                            }
                            ?>
                            <li class="page-item <?= $nav_left_status ?>">
                                <a class="page-link" href="?page=1<?= $sort_page ?><?= $search_page ?>" >First</a>
                            </li>
                            <li class="page-item <?= $nav_left_status ?>">
                                <a class="page-link" href="?page=<?= $curr_page-1 ?><?= $sort_page ?><?= $search_page ?>" >Previous</a>
                            </li>
                            <?php 
                            // pages
                            for ($page=1; $page <= $number_of_pages; $page++) { 
                                $page_status='';
                                if($curr_page==$page){
                                    $page_status = 'active';
                                }
                                ?>
                                <li class="page-item <?= $page_status ?>"><a class="page-link" href="?page=<?= $page ?><?= $sort_page ?><?= $search_page ?>"><?= $page ?></a></li>
                                <?php
                            }
                            ?>
                            <li class="page-item <?= $nav_right_status ?>">
                                <a class="page-link " href="?page=<?= $curr_page+1 ?><?= $sort_page ?><?= $search_page ?>" >Next</a>
                            </li>
                            <li class="page-item <?= $nav_right_status ?>">
                            <a class="page-link" href="?page=<?= $number_of_pages ?><?= $sort_page ?><?= $search_page ?>">Last</a>
                            </li>
                        </ul>
                        </nav>
                    </div>
                </div>
                <?php
            // ####################################

            }elseif(isset($_GET['category'])){
                $category_slug = $_GET['category'];
                ?>
                <div class="dropdown">
                
                <form action="" method="GET">
                    <select class="category-dropdown" onchange="submit()" name="category" >
                        <option <?= isset($_GET["category"])?($_GET['category']== "category"?'selected':''):'' ?> value="category">Category</option>
                <?php 
                    $cat_sql = "SELECT * FROM categories;";
                    $cat_stmt = mysqli_query($conn, $cat_sql);
                    if($cat_stmt){
                        foreach($cat_stmt as $cat_row){
                            ?>
                            <option <?= isset($_GET["category"])?($_GET['category']== $cat_row['slug']?'selected':''):'' ?> value="<?= $cat_row['slug']?>"><?= $cat_row['name']?></option>
                            <?php
                        }
                    }else{
                        ?>
                        <option value="category">No Categories</option>
                        <?php
                    }
                ?>
                    </select>
                </form>
                <form action="" method="GET">
                    <select class="sort" onchange="submit()" name="sort" >
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "sort"?'selected':''):'' ?> value="sort">Sort</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "name ASC"?'selected':''):'' ?> value="name ASC">A-Z</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "name DESC"?'selected':''):'' ?> value="name DESC">Z-A</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "price DESC"?'selected':''):'' ?> value="price DESC">Price: High to Low</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "price ASC"?'selected':''):'' ?> value="price ASC">Price: Low to High</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "created_at ASC"?'selected':''):'' ?> value="created_at ASC">Newest to Oldest</option>
                        <option <?= isset($_GET["sort"])?($_GET['sort']== "created_at DESC"?'selected':''):'' ?> value="created_at DESC">Oldest to Newest</option>
                    </select>
                    <input type="hidden" name="category" value="<?= $category_slug ?>">
                </form>
                </div>
                </div>
                
                <!-- tranfer HEREEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE -->
                <div class="items">
                    <div class="upper-items">
                        <?php
                            if(!isset($_GET['page'])){
                                $page=1;
                            }else{
                                $page = (int)$_GET['page'];
                            }
                            // page variables
                            $res_per_page = 40;
                            $num_pro_sql = "SELECT p.* FROM products p, categories c WHERE p.status='0' AND p.category_id=c.category_id AND c.slug='$category_slug'";
                            $num_pro_stmt = mysqli_query($conn, $num_pro_sql);
                            $num_res = mysqli_num_rows($num_pro_stmt);
                            $number_of_pages = ceil($num_res/$res_per_page);
                            $page_limit = ($page-1)*$res_per_page;

                            // get limited products
                            $pro_sql = "SELECT p.* FROM products p, categories c WHERE p.status='0' AND p.category_id=c.category_id AND c.slug='$category_slug'";
                            $pro_limit_sql = " LIMIT $page_limit, $res_per_page";
                            if(isset($_GET['sort'])){
                                $concat_sql = ' ORDER BY '.$_GET['sort'];
                                if($_GET['sort'] == 'sort'){
                                    $concat_sql = '';
                                }
                                $pro_sql = $pro_sql.$concat_sql.$pro_limit_sql;
                            }else{
                                $pro_sql = $pro_sql.$pro_limit_sql;
                            }

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
                                    <h4 class="text-danger">No Product Available</h4>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="lower-pagination">
                            <nav aria-label="...">
                            <ul class="pagination pagination-sm">
                                <?php
                                $curr_page = $page;
                                $nav_left_status = '';
                                $nav_right_status = '';
                                if($curr_page==1){
                                    $nav_left_status = 'disabled';
                                }
                                if($curr_page==$number_of_pages){
                                    $nav_right_status = 'disabled';
                                }

                                // if sort exist
                                $sort_page = '';
                                if(isset($_GET['sort'])){
                                    $sort_page = '&sort='.$_GET['sort'];
                                }
                                // if category exist
                                $category_page = '';
                                if(isset($_GET['category'])){
                                    $category_page = '&category='.$_GET['category'];
                                }
                                ?>
                                <li class="page-item <?= $nav_left_status ?>">
                                    <a class="page-link" href="?page=1<?= $sort_page ?><?= $category_page ?>" >First</a>
                                </li>
                                <li class="page-item <?= $nav_left_status ?>">
                                    <a class="page-link" href="?page=<?= $curr_page-1 ?><?= $sort_page ?><?= $category_page ?>" >Previous</a>
                                </li>
                                <?php 
                                // pages
                                for ($page=1; $page <= $number_of_pages; $page++) { 
                                    $page_status='';
                                    if($curr_page==$page){
                                        $page_status = 'active';
                                    }
                                    ?>
                                    <li class="page-item <?= $page_status ?>"><a class="page-link" href="?page=<?= $page ?><?= $sort_page ?><?= $category_page ?>"><?= $page ?></a></li>
                                    <?php
                                }
                                ?>
                                <li class="page-item <?= $nav_right_status ?>">
                                    <a class="page-link " href="?page=<?= $curr_page+1 ?><?= $sort_page ?><?= $category_page ?>" >Next</a>
                                </li>
                                <li class="page-item <?= $nav_right_status ?>">
                                <a class="page-link" href="?page=<?= $number_of_pages ?><?= $sort_page ?><?= $category_page ?>">Last</a>
                                </li>
                            </ul>
                            </nav>
                        </div>
                    </div>
                <?php

            // ####################################
            }else{
            // ####################################



                ?>
                    <div class="dropdown">
                    <form action="" method="GET">
                        <select class="category-dropdown" onchange="submit()" name="category" >
                            <option <?= isset($_GET["category"])?($_GET['category']== "category"?'selected':''):'' ?> value="category">Category</option>
                    <?php 
                        $cat_sql = "SELECT * FROM categories;";
                        $cat_stmt = mysqli_query($conn, $cat_sql);
                        if($cat_stmt){
                            foreach($cat_stmt as $cat_row){
                                ?>
                                <option <?= isset($_GET["category"])?($_GET['category']== $cat_row['slug']?'selected':''):'' ?> value="<?= $cat_row['slug']?>"><?= $cat_row['name']?></option>
                                <?php
                            }
                        }else{
                            ?>
                            <option value="category">No Categories</option>
                            <?php
                        }
                    ?>
                        </select>
                    </form>
                    <form action="" method="GET">
                        <select class="sort" onchange="submit()" name="sort" >
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "sort"?'selected':''):'' ?> value="sort">Sort</option>
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "name ASC"?'selected':''):'' ?> value="name ASC">A-Z</option>
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "name DESC"?'selected':''):'' ?> value="name DESC">Z-A</option>
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "price DESC"?'selected':''):'' ?> value="price DESC">Price: High to Low</option>
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "price ASC"?'selected':''):'' ?> value="price ASC">Price: Low to High</option>
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "created_at ASC"?'selected':''):'' ?> value="created_at ASC">Newest to Oldest</option>
                            <option <?= isset($_GET["sort"])?($_GET['sort']== "created_at DESC"?'selected':''):'' ?> value="created_at DESC">Oldest to Newest</option>
                        </select>
                    </form>
                    
                    </div>
                </div>
                <div class="items">
                    <div class="upper-items">
                    <?php
                        if(!isset($_GET['page'])){
                            $page=1;
                        }else{
                            $page = (int)$_GET['page'];
                        }
                        // page variables
                        $res_per_page = 40;
                        $num_pro_sql = "SELECT * FROM products WHERE status='0'";
                        $num_pro_stmt = mysqli_query($conn, $num_pro_sql);
                        $num_res = mysqli_num_rows($num_pro_stmt);
                        $number_of_pages = ceil($num_res/$res_per_page);
                        $page_limit = ($page-1)*$res_per_page;

                        // get limited products
                        $pro_sql = "SELECT * FROM products WHERE status='0'";
                        $pro_limit_sql = " LIMIT $page_limit, $res_per_page";
                        if(isset($_GET['sort'])){
                            $concat_sql = ' ORDER BY '.$_GET['sort'];
                            if($_GET['sort'] == 'sort'){
                                $concat_sql = '';
                            }
                            $pro_sql = $pro_sql.$concat_sql.$pro_limit_sql;
                        }else{
                            $pro_sql = $pro_sql.$pro_limit_sql;
                        }

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
                                <h4 class="text-danger">No Product Available</h4>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="lower-pagination">
                        <nav aria-label="...">
                        <ul class="pagination pagination-sm">
                            <?php
                            $curr_page = $page;
                            $nav_left_status = '';
                            $nav_right_status = '';
                            if($curr_page==1){
                                $nav_left_status = 'disabled';
                            }
                            if($curr_page==$number_of_pages){
                                $nav_right_status = 'disabled';
                            }

                            // if sort exist
                            $sort_page = '';
                            if(isset($_GET['sort'])){
                                $sort_page = '&sort='.$_GET['sort'];
                            }
                            ?>
                            <li class="page-item <?= $nav_left_status ?>">
                                <a class="page-link" href="?page=1<?= $sort_page ?>" >First</a>
                            </li>
                            <li class="page-item <?= $nav_left_status ?>">
                                <a class="page-link" href="?page=<?= $curr_page-1 ?><?= $sort_page ?>" >Previous</a>
                            </li>
                            <?php 
                            // pages
                            for ($page=1; $page <= $number_of_pages; $page++) { 
                                $page_status='';
                                if($curr_page==$page){
                                    $page_status = 'active';
                                }
                                ?>
                                <li class="page-item <?= $page_status ?>"><a class="page-link" href="?page=<?= $page ?><?= $sort_page ?>"><?= $page ?></a></li>
                                <?php
                            }
                            ?>
                            <li class="page-item <?= $nav_right_status ?>">
                                <a class="page-link " href="?page=<?= $curr_page+1 ?><?= $sort_page ?>" >Next</a>
                            </li>
                            <li class="page-item <?= $nav_right_status ?>">
                            <a class="page-link" href="?page=<?= $number_of_pages ?><?= $sort_page ?>">Last</a>
                            </li>
                        </ul>
                        </nav>
                    </div>
                </div>
                <?php
            }
        ?>
        
    </div>
</div>



<?php include_once 'includes/footer.php'; ?>
    