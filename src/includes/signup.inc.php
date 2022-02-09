<?php

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $pwdRepeat = $_POST['cpassword'];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(emptyInputSignup($fname, $lname, $email, $pwd, $pwdRepeat) !== false){
        header("location: ../signup?error=emptyinput");
        exit();
    }
    if(invalidUid($fname) !== false){
        header("location: ../signup?error=invaliduid");
        exit();
    }
    if(invalidUid($lname) !== false){
        header("location: ../signup?error=invaliduid");
        exit();
    }
    if(invalidEmail($email) !== false){
        header("location: ../signup?error=invalidEmail");
        exit();
    }
    if(pwdMatch($pwd, $pwdRepeat) !== false){
        header("location: ../signup?error=pwddontmatch");
        exit();
    }
    if(emailExists($conn, $email) !== false){
        header("location: ../signup?error=uidTaken");
        exit();
    }

    createUser($conn,$fname, $lname, $email, $pwd);

}
else{
    header('location: ../signup.php');
    exit();
} 