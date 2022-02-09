<?php include_once 'includes/header.php'; ?>
<div class="about-page">

    <?php 
        $about_sql = "SELECT * FROM about_us WHERE about_id='1' LIMIT 1";
        $about_stmt = mysqli_query($conn, $about_sql);
        if(mysqli_num_rows($about_stmt)>0){
            $about_row = mysqli_fetch_array($about_stmt);
        }else{
            header("Location: 404");
        }

    ?>
    <div class="image">
        <img src="./uploads/about_us/<?= $about_row['image'] ?>" >
    </div>
    <div class="description">
        <h2 class="title"><?= $about_row['title'] ?></h2>
        <p><?= $about_row['description'] ?></p>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>