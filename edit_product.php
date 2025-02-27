<?php
$page_title = 'Edit Product';
require_once('includes/load.php');

// Check user permission level
page_require_level(2);

// Fetch product details
$product_id = (int)$_GET['id'];
$product = find_by_id('products', $product_id);

if (!$product) {
    $session->msg("d", "Missing product ID.");
    redirect('product.php');
}

// Fetch all categories and media for dropdowns
$all_categories = find_all('categories');
$all_photo = find_all('media');

// Handle form submission
if (isset($_POST['product'])) {
    // Required fields
    $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
    validate_fields($req_fields);

    if (empty($errors)) {
        // Sanitize and prepare data
        $p_name = remove_junk($db->escape($_POST['product-title']));
        $p_cat = (int)$_POST['product-categorie'];
        $p_qty = (int)$_POST['product-quantity'];
        $p_buy = remove_junk($db->escape($_POST['buying-price']));
        $p_sale = remove_junk($db->escape($_POST['saleing-price']));
        $media_id = isset($_POST['product-photo']) && $_POST['product-photo'] !== "" 
                    ? (int)$_POST['product-photo'] 
                    : 0;

        // Build the SQL query
        $query = "UPDATE products SET 
                  name = '{$p_name}', 
                  quantity = '{$p_qty}', 
                  buy_price = '{$p_buy}', 
                  sale_price = '{$p_sale}', 
                  categorie_id = '{$p_cat}', 
                  media_id = '{$media_id}' 
                  WHERE id = '{$product_id}'";

        // Execute the query
        $result = $db->query($query);

        if ($result && $db->affected_rows() > 0) {
            $session->msg('s', "Product updated successfully.");
            redirect('product.php');
        } else {
            $session->msg('d', "Failed to update product.");
            redirect('edit_product.php?id=' . $product_id);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_product.php?id=' . $product_id);
    }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($session->msg()); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Edit Product</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="col-md-7">
                <form method="post" action="edit_product.php?id=<?php echo $product_id; ?>">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-th-large"></i>
                            </span>
                            <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']); ?>" placeholder="Product Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="product-categorie">
                                    <option value="">Select a category</option>
                                    <?php foreach ($all_categories as $cat): ?>
                                        <option value="<?php echo (int)$cat['id']; ?>" <?php echo ($product['categorie_id'] === $cat['id']) ? "selected" : ""; ?>>
                                            <?php echo remove_junk($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="product-photo">
                                    <option value="">No image</option>
                                    <?php foreach ($all_photo as $photo): ?>
                                        <option value="<?php echo (int)$photo['id']; ?>" <?php echo ($product['media_id'] === $photo['id']) ? "selected" : ""; ?>>
                                            <?php echo $photo['file_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Quantity</label>
                                <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Buying Price</label>
                                <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Selling Price</label>
                                <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']); ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="product" class="btn btn-danger">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>