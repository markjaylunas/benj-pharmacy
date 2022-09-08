<?php 
require_once './includes/dbh.inc.php';



if(!isset($_POST['btn_cancel_order'])||!isset($_POST['user_id'])||!isset($_POST['order_id'])){
    header('Location: account');
    exit();
}

$user_id = $_POST['user_id'];
$order_id = $_POST['order_id'];






?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Cancellation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/benj_logo.svg">
</head>
<body>

    <nav class="navbar navbar-light bg-light">
        <a href="./index"><span class="navbar-brand mb-0 h1 text-primary">Benj Pharmacy</span></a>
        
    </nav>
    <div class="container ">

        <div class="row my-3">
            <div class="col-md-4 offset-md-4 form">
                <form method="POST" action="./cancel-order-post" autocomplete="off">
                    <h2 class="text-center">Order Cancellation</h2>
                        <div class="alert alert-success text-center">
                            Enter your reason for cancellation
                        </div>
                    <div class="form-group">
                        <textarea class="form-control"  rows="5" cols="10" type="text" name="cancellation_reason" placeholder="Enter here..."></textarea>
                    </div>
                    <div class="alert alert-secondary" role="alert">
                        <span class="">Note: </span>Cancellation process may take several days.
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="<?php echo $_POST['user_id']; ?>">
                        <input type="hidden" name="order_id" value="<?php echo $_POST['order_id']; ?>">
                        <input class="form-control button button" type="submit" name="cancel_confirmed" value="Submit">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>