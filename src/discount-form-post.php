<?php
require_once './includes/dbh.inc.php';

function locator($error){
    header('Location: ' . $_SERVER['HTTP_REFERER'].'&error='.$error);
    exit();
}
if(!isset($_POST['discount_form_submit'])){
    locator('discount-form-post-exit');
}
$user_id = $_POST['user_id'];
$files = $_FILES['filetoupload'];
$filenames = array_filter($_FILES['filetoupload']['name']);
$files_count = count($filenames);

foreach($filenames as $key=>$value){
    $fileName = $_FILES['filetoupload']['name'][$key];
    $fileTmpName = $_FILES['filetoupload']['tmp_name'][$key];
    $fileSize = $_FILES['filetoupload']['size'][$key];
    $fileError = $_FILES['filetoupload']['error'][$key];
    $fileType = $_FILES['filetoupload']['type'][$key];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg','jpeg','png');

    if($fileError !== 0) locator('A-file-error');
    if($fileSize > 10485760) locator('A-file-size-is-too-big');
    if(!in_array($fileActualExt, $allowed)) locator('A-file-type-not-allowed');

}


// check if exists to discounts table
$discount_exists = false;
$discount_select_sql = "SELECT * FROM discounts WHERE user_id='$user_id' LIMIT 1;";
$discount_select_stmt = mysqli_query($conn, $discount_select_sql);
if(mysqli_num_rows($discount_select_stmt)>0){
    $discount_exists = true;
    echo 'exists';
}

if(!$discount_exists){
    // insert to discounts table
    $discount_insert_sql = "INSERT INTO discounts (`user_id`, `approval`) VALUES ('$user_id', '0');";
    $discount_insert_stmt = mysqli_query($conn, $discount_insert_sql);
    if(!$discount_insert_stmt){
        locator('discount-insert-failed');
    }
}





foreach($filenames as $key=>$value){
    $fileName = $_FILES['filetoupload']['name'][$key];
    $fileTmpName = $_FILES['filetoupload']['tmp_name'][$key];
    $fileSize = $_FILES['filetoupload']['size'][$key];
    $fileError = $_FILES['filetoupload']['error'][$key];
    $fileType = $_FILES['filetoupload']['type'][$key];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    $fileNameNew = $user_id.uniqid('',true).'.'.$fileActualExt;
    $fileDestination = './uploads/discounts/'.$fileNameNew;

    $discount_file_insert_sql = "INSERT INTO discounts_files (`user_id`, `filename`, `filename_alt`) VALUES ('$user_id', '$fileNameNew', '$fileName');";
    $discount_file_insert_stmt = mysqli_query($conn, $discount_file_insert_sql);
    if(!$discount_file_insert_stmt){
        locator('discount-file-insert-failed-'.$key);
    }
    if(!move_uploaded_file($fileTmpName, $fileDestination)) locator('A-file-failed-to-upload');
    echo 'success upload key->'.$key;
}
header('Location: checkout?discount=success-application');
exit();
?>