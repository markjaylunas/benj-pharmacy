<?php 
ob_start();
include('./config/dbcon.php');
include('./includes/header.php');
include('./message.php');
?>
<div class="container p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Category<a href="category-view" class="btn btn-danger float-end">Back</a></h4>
                </div>
                <div class="card-body">
                    <form action="includes/functions" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" required class="form-control" >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slug">Slug(URL)</label>
                                <input type="text" name="slug" placeholder="Optional" class="form-control" >
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" rows="4" class="form-control" ></textarea>
                            </div>
                            <hr>
                            <h5>SEO</h5>
                            <div class="col-md-12 mb-3">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" name="meta_title" max="191" required class="form-control" >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meta_description">Meta Description</label>
                                <textarea name="meta_description" rows="4" required class="form-control" ></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="meta_keyword">Meta Keyword</label>
                                <textarea name="meta_keyword" rows="4" required class="form-control" ></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-primary" type="submit" name="category_add" >Add Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
include('./includes/footer.php');
include('./includes/scripts.php');
?>