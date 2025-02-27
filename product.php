<?php
$page_title = 'All Products';
require_once('includes/load.php');
// Check user permission level
page_require_level(2);
$products = join_product_table();

// Fetch product ingredients along with measurement units
function get_product_ingredients($product_id) {
    global $db;
    $sql = "SELECT i.name, pi.quantity, i.unit FROM product_ingredients pi
            JOIN ingredients i ON pi.ingredient_id = i.id
            WHERE pi.product_id = '{$product_id}'";
    return $db->query($sql);
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Add New</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Photo</th>
                            <th> Product Title </th>
                            <th class="text-center" style="width: 10%;"> Categories </th>
                            <th class="text-center" style="width: 10%;"> Selling Price </th>
                            <th class="text-center" style="width: 20%;"> Ingredients </th>
                            <th class="text-center" style="width: 10%;"> Product Added </th>
                            <th class="text-center" style="width: 100px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="text-center"><?php echo count_id();?></td>
                            <td>
                                <?php
                                $image = 'no_image.png'; // Default image
                                if (isset($product['media_id']) && $product['media_id'] !== '0' && !empty($product['image'])) {
                                    $image = $product['image'];
                                }
                                ?>
                                <img class="img-avatar img-circle" src="uploads/products/<?php echo $image; ?>" alt="">
                            </td>
                            <td> <?php echo remove_junk($product['name']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                            <td class="text-center">
                                <?php
                                // Fetch ingredients for the product
                                $ingredients = get_product_ingredients($product['id']);
                                if ($ingredients->num_rows > 0) {
                                    while ($ingredient = $ingredients->fetch_assoc()) {
                                        echo $ingredient['name'] . ' (' . $ingredient['quantity'] . ' ' . $ingredient['unit'] . ')<br>';
                                    }
                                } else {
                                    echo 'No ingredients';
                                }
                                ?>
                            </td>
                            <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>