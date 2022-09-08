<?php 
require_once './includes/dbh.inc.php';



if(!isset($_GET['user_id'])){
    header('Location: ' . $_SERVER['HTTP_REFERER'].'?error=discount-form-noUserID');
    exit();
}

$user_id = $_GET['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Senior Citizen / PWD Discount Form</title>
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
                <form action="discount-form-post.php" method="post" enctype="multipart/form-data">
                    <h2 class="text-center">Senior Citizen / PWD Discount Form</h2>
                        <div class="alert alert-success text-center">
                            Please upload your Senior Citizen / PWD Identification Card
                        </div>
                        <div class="mb-3 my-4">
                            <input   class="form-control p-1" type="file" id="filetoupload" name="filetoupload[]" multiple>
                            <label style="font-size: .8rem;" for="formFileMultiple" class="form-label">*You can select multiple files to upload*</label>
                            <label style="font-size: .8rem;" for="formFileMultiple" class="form-label">*Allowed files to be uploaded: .jpg, .jpeg, .png*</label>
                        </div>
                    <div class="alert alert-secondary" role="alert">
                        <span class="">Note: </span>Approval process may take several days.
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
                        <input class="form-control btn-primary" type="submit" name="discount_form_submit" value="Submit">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>