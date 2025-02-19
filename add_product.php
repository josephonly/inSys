<?php
$page_title = 'Add Product';
require_once('includes/load.php');
page_require_level(2);

$all_categories = find_all('categories');
$all_photos = find_all('media');
$all_ingredients = find_all('ingredients'); // Fetch all ingredients from the database

if (isset($_POST['add_product'])) {
    $req_fields = array('product-title', 'product-categorie', 'saleing-price');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
        $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
        $media_id = !empty($_POST['product-photo']) ? (int)$_POST['product-photo'] : 0;
        $date    = make_date();

        $query  = "INSERT INTO products (name, sale_price, categorie_id, media_id, date) ";
        $query .= "VALUES ('{$p_name}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}') ";
        $query .= "ON DUPLICATE KEY UPDATE name='{$p_name}'";

        if ($db->query($query)) {
            $session->msg('s', "Product added successfully!");
            redirect('add_product.php', false);
        } else {
            $session->msg('d', 'Failed to add product.');
            redirect('add_product.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12"><?php echo display_msg($msg); ?></div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-th"></span> Add New Product</strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_product.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="product-title" placeholder="Product Name">
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="product-categorie">
                            <option value="">Select Product Category</option>
                            <?php foreach ($all_categories as $cat) : ?>
                                <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="product-photo">
                            <option value="">Select Product Image</option>
                            <?php foreach ($all_photos as $photo) : ?>
                                <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                    </div>

                    <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-th"></span> Select Ingredients</strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_product.php">
                    <div class="form-group">
                        <select class="form-control" name="product-ingredients[]" multiple>
                            <option value="">Select Ingredients</option>
                            <?php foreach ($all_ingredients as $ingredient) : ?>
                                <option value="<?php echo (int)$ingredient['id'] ?>"><?php echo $ingredient['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" name="add_ingredients" class="btn btn-primary">Add Ingredients</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
