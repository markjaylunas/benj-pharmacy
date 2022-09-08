<?php
function emptyInputSignup($fname, $lname, $email, $pwd, $pwdRepeat)
{
    if (empty($fname) || empty($lname) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
        return true;
    } else {
        return false;
    }
}
function invalidUid($uname)
{
    // if (!preg_match("/^[a-zA-Z0-9]*$/", $uname)) {
    if (!preg_match("/^(?![\s.]+$)[a-zA-Z\s.]*$/", $uname)) {

        return true;
    } else {
        return false;
    }
}
function invalidEmail($email)
{

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}
function pwdMatch($pwd, $pwdRepeat)
{
    if ($pwd !== $pwdRepeat) {
        return true;
    } else {
        return false;
    }
}
function emailExists($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function uidExists($conn, $uid)
{
    $sql = "SELECT * FROM users WHERE user_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}


function createUser($conn, $fname, $lname, $email, $pwd)
{
    $code = rand(999999, 111111);
    $status = "notverified";
    $sql = "INSERT INTO users (user_id, first_name,  last_name, email, password, code, status) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=stmtfailed");
        exit();
    }
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    $uid = md5(uniqid());
    while (uidExists($conn, $uid) !== false) {
        $uid = md5(uniqid());
    }
    mysqli_stmt_bind_param($stmt, "sssssss", $uid, $fname, $lname, $email, $hashedPwd, $code, $status);
    mysqli_stmt_execute($stmt);
    
    $subject = "Benj Pharmacy Online - Email Verification Code";
    $message = "Your verification code is $code";
    $sender = "From: official@benjpharmacy.online";

    if(mail($email, $subject, $message, $sender)){
        // $_SESSION['email'] = $email;
        // $_SESSION['password'] = $password;
        $location = 'location: ../user-otp?email=';
        $location .= $email;
        header($location);
        exit();
    }else{
        header('location: ../signup?error=otp-sending-failed');
        exit();
    }

    mysqli_stmt_close($stmt);
    header('location: ../signup?error=create-user');
    exit();
}

function emptyInputLogin($email, $pwd)
{
    if (empty($email) || empty($pwd)) {
        return true;
    } else {
        return false;
    }
}


function getUserInfo($conn, $email)
{
    session_start();
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        if($row['status'] != 'verified') {
            mysqli_stmt_close($stmt);
            header('location: ../user-otp?email='.$row['email']); 
            exit();
        }
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['fname'] = $row['first_name'];
        $_SESSION['lname'] = $row['last_name'];
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
    header('location: ../index'); 
    exit();
}

function loginUser($conn, $email, $pwd)
{
    $emailExists = emailExists($conn, $email);

    if ($emailExists === false) {
        header('location: ../login?error=wronglogin');
        exit();
    }

    $pwdHashed = $emailExists['password'];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header('location: ../login?error=wronglogin');
        exit();
    } elseif ($checkPwd === true) {
        session_start();
        $_SESSION['email'] = $emailExists['email'];
        getUserInfo($conn, $_SESSION['email']);
        header('location: ../index');
        exit();
    }
}


function getProducts($conn, $offset, $limit){
    $sql = "SELECT * FROM products WHERE status=0 LIMIT '$offset', '$limit'";
    $stmt = mysqli_query($conn, $sql);
    if(mysqli_num_rows($stmt)>0){
        $result = mysqli_fetch_array($stmt);
        return $result;
    }else{
        return 'failed';
    }
}