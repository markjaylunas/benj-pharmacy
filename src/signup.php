<?php include_once 'includes/header.php'; 


?>

<div class="login-page">
    <div class="left">
        <div class="login">
            <h1>Sign Up</h1>
            <form action="includes/signup.inc.php" method="POST">
                <input type="text" name="fname" placeholder="First Name" required>
                <input type="text" name="lname" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="cpassword" placeholder="Confirm Password" required>
                <input type="submit" value="Create Account" name="submit">
            </form>
            <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'emptyinput') {
                        echo '<p class="tag">Fill in the fields!</p>';
                    } elseif ($_GET['error'] == 'invaliduid') {
                        echo '<p class="tag">Invalid Username!</p>';
                    } elseif ($_GET['error'] == 'invalidEmail') {
                        echo '<p class="tag">Invalid Email!</p>';
                    }  elseif ($_GET['error'] == 'pwddontmatch') {
                        echo '<p class="tag">Password don\'t match!</p>';
                    }  elseif ($_GET['error'] == 'uidTaken') {
                        echo '<p class="tag">Username taken!</p>';
                    }  elseif ($_GET['error'] == 'none') {
                        echo '<p class="tag">You have signed up!</p>';
                    }  
                }
            ?>
            <p>Already have an account? <a href="./login">Login</a></p>
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