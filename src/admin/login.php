<?php 
    session_start();
    if(isset($_SESSION['auth_user'])){
        if(!isset($_SESSION['message'])){
            $_SESSION['message'] = 'You are already logged in';
        }
        header('Location: index');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <section style="height:100vh;" class="">
        <div class="container py-2 h-50">
            <div class="row d-flex justify-content-center align-items-center h-80">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-light mt-3" style="border-radius: 1rem;">
                        <div class="card-body p-5 ">
                            <div class=" mt-md-4 pb-5">
                                <h2 class="fw-bold pb-5 text-uppercase text-center">Admin Login</h2>
                                <form action="login.inc.php" method="post" >
                                    <?php 
                                    
                                    include 'message.php'?>
                                    
                                    <div class="form-outline mb-4">
                                        <label class="form-label pt-2" for="typeEmailX">Username</label>
                                        <input type="text" name="username" class="form-control form-control-lg" />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label pt-2" for="password">Password</label>
                                        <input type="password" name="password" class="form-control form-control-lg" />
                                    </div>
                                    <div class="row">
                                        <input class="btn btn-primary my-1 px-5" name="admin_login" type="submit" value="Login"/>

                                    </div>
                                </form>
                                <!-- <p class="small "><a class="text-50" href="#!">Forgot password?</a></p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
</body>
</html>