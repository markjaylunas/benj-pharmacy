<?php
    session_start();
    include('./includes/dbh.inc.php')
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        
        <link rel="stylesheet" href="./css/style.css?v=1.4">

        <title>Benj Pharmacy</title>
    </head>
    <body>
            <header>
                <div class="upper-nav">
                    <div class="logo"><a href="index" class="logo-name">Benj Pharmacy</a></div>
                    <form class="searchbar-lg" action="./all-products" method="GET">
                        <div class="search-bar">
                            <input type="text" required name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" placeholder="Search Products" id="search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    <div class="right-nav">
                        <div class="user-nav">
                        <?php
                            if(isset($_SESSION['email'])){
                                $fname = $_SESSION['fname'];

                                
                                if(isset($_SESSION['user_id'])){
                                    $user_id = $_SESSION['user_id'];
                                    
                                    $cart_sql = "SELECT p.*,c.* FROM products p, cart c WHERE p.product_id=c.product_id AND c.user_id ='$user_id';";
                                    $cart_stmt = mysqli_query($conn, $cart_sql);
                                    if(mysqli_num_rows($cart_stmt)>0){
                                        $cart_count = mysqli_num_rows($cart_stmt);
                                    }else{
                                        $cart_count=0;
                                    }
                                }

                                echo "<a href='./cart' class='cart'><i class='fa fa-shopping-cart'></i> ($cart_count)</a>";
                                echo "<a class='acc-lg' href='./account' class='username'><i class='fa fa-user'></i> $fname</a>";
                                echo "<a class='acc-sm' href='./account' class='username'><i class='fa fa-user'></i></a>";
                                echo "<a href='./includes/logout.inc.php' class='logout'><i class='fa fa-sign-out'></i></a>";
                            }else{
                                echo '<a href="login">Login</a>';
                                echo '<a href="signup">Sign Up</a>';
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="searchbar-sm-container">
                    <form class="searchbar-sm" action="./all-products" method="GET">
                        <div class="search-bar">
                            <input type="text" required name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" placeholder="Search Products" id="search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="lower-nav">
                    <nav>
                        <ul>
                            <li><a href="index">Home</a></li>
                            <li><a href="all-products">All Products</a></li>
                            <li><a href="about">About Us</a></li>
                        </ul>
                    </nav>
                </div>
            </header>