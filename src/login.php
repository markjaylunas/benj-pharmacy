<?php include_once 'includes/header.php'; 


?>

<div class="login-page">
<div class="left">
        <div class="login">
            <h1>Login</h1>
            <form action="includes/login.inc.php" method="post">
                <input type="text" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login" name="submit">
            </form>
            
            <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'emptyinput') {
                        echo '<p class="tag text-danger ">Fill in the fields!</p>';
                    } elseif ($_GET['error'] == 'wronglogin') {
                        echo '<p class="tag text-danger">Incorrect login information!</p>';
                    }
                }
            ?>

            <p>Don't have an account? <a href="signup">Create account</a></p>
        </div>
    </div>
    <div class="right">
        <p>Track your order history and store addresses for fast and easy checkouts.</p>
        <div class="img-div">
            <img src="./images/welcome.png" alt="welcome image">
        </div>
    </div>
    

</div>




<?php include_once 'includes/footer.php'; ?>