
<?php 
require_once "./includes/dbh.inc.php";
require_once "./includes/functions.inc.php";
if(!isset($_GET['email'])){
    header('location: user-otp?error=no-email');
    exit();
}
$email = $_GET['email'];
$info = "We've sent a verification code to your email - ".$email;

if(isset($_GET['check'])){
    $email = $_GET['email'];
    $info = "";
    $otp_code = mysqli_real_escape_string($conn, $_GET['otp-code']);
    $otp_sql = "SELECT * FROM users WHERE code = '$otp_code' AND email = '$email'";
    $otp_res = mysqli_query($conn, $otp_sql);
    if(mysqli_num_rows($otp_res) > 0){
        echo 'found otp';
        $fetch_data = mysqli_fetch_assoc($otp_res);
        $user_id = $fetch_data['user_id'];
        $fetch_code = $fetch_data['code'];
        $fetch_email = $fetch_data['email'];

        $code = '0';
        $status = 'verified';
        $update_otp_sql = "UPDATE users SET code = '$code', status = '$status' WHERE user_id = '$user_id'";
        $update_res = mysqli_query($conn, $update_otp_sql);
        if($update_res){
            getUserInfo($conn, $fetch_email);
        }else{
            header('location: ./user-otp?error=failed-update&email='.$fetch_email);
            exit();
        }
    }else{
        header('location: ./user-otp?error=wrong-otp-code&email='.$fetch_email);
        exit();
    }
}
if(isset($_GET['resend'])){
    $email = $_GET['email'];
    $resend_select_sql = "SELECT * FROM users WHERE email = '$email'";
    $resend_select_res = mysqli_query($conn, $resend_select_sql);
    if(mysqli_num_rows($resend_select_res) > 0){
        $fetch_data = mysqli_fetch_assoc($resend_select_res);
        $fetch_email = $fetch_data['email'];
        $fetch_code = $fetch_data['code'];
        $code = strval(rand(999999, 111111));
        while($fetch_code == $code) $code = strval(rand(999999, 111111));

        $update_otp_sql = "UPDATE users SET code = '$code' WHERE email = '$fetch_email'";
        $update_res = mysqli_query($conn, $update_otp_sql);
        if($update_res){
            
            $subject = "Benj Pharmacy Online - Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: official@benjpharmacy.online";
            
            if(mail($email, $subject, $message, $sender)){

                header('location: ./user-otp?resent=true&email='.$fetch_email);
                exit();
            }
            header('location: ./user-otp?resent=partial&email='.$fetch_email);
            exit();
        }else{
            header('location: ./user-otp?error=failed-update&email='.$fetch_email);
            exit();
        }
    }else{
        header('location: ./user-otp?error=wrong-otp-code&email='.$fetch_email);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/benj_logo.svg">
</head>
<body>

    <nav class="navbar navbar-light bg-light">
        <a href="./index"><span class="navbar-brand mb-0 h1 text-primary">Benj Pharmacy</span></a>
        <ul class="navbar-nav d-flex flex-row">
            <li class="nav-item mx-4">
                <a class="nav-link" href="./login">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./signup.php">Sign Up</a>
            </li>
        </ul>
    </nav>
    <div class="container ">

        <div class="row my-3">
            <div class="col-md-4 offset-md-4 form">
                <form method="GET" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                        <div class="alert alert-success text-center">
                            <?= $info ?>
                        </div>
                    <div class="form-group">
                        <input class="form-control" type="number" name="otp-code" placeholder="Enter verification code">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
                        <input type="hidden" id="resent" name="resent" value="<?php echo $_GET['resent']; ?>">
                        <input class="form-control button" type="submit" name="check" value="Submit">
                    </div>
                    <div class="">
                        <span>
                            Don't recieve the OTP?
                        </span>
                        <input class="text-primary" <?php $_GET['resent']=='true' ? 'disabled':'' ?> id="resend_countdown" style="border:none;background:white;padding:0;margin:0;" type="submit" name="resend" value="Resend OTP">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        let resent_doc = document.getElementById("resent").value
        let resend_countdown = document.getElementById("resend_countdown")
        

        if(resent_doc == "true"){
            resend_countdown.classList.remove('text-primary')
            resend_countdown.classList.add('text-muted')
            let timerOn = true;

            function timer(remaining) {
            var m = Math.floor(remaining / 60);
            var s = remaining % 60;
            
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            resend_countdown.value = 'Resend OTP ' + m + ':' + s + '';
            remaining -= 1;
            
            if(remaining >= 0 && timerOn) {
                setTimeout(function() {
                    timer(remaining);
                }, 1000);
                return;
            }

            if(!timerOn) {
                // Do validate stuff here
                
                return;
            }
            
            resend_countdown.disabled = false;
            resend_countdown.value = 'Resend OTP' 
            resend_countdown.classList.remove('text-muted')
            resend_countdown.classList.add('text-primary')

            }
            timer(120);
        }
    
    </script>
</body>
</html>