<?php
$page_title = 'Ingredient Inventory';
require_once('includes/load.php');
page_require_level(2);

$all_ingredients = find_all('ingredients');

if (isset($_POST['add_ingredient'])) {
    $req_fields = array('ingredient-name', 'ingredient-stock', 'ingredient-unit');
    validate_fields($req_fields);

    if (empty($errors)) {
        $i_name  = remove_junk($db->escape($_POST['ingredient-name']));
        $i_stock = (int) $_POST['ingredient-stock'];
        $i_unit  = remove_junk($db->escape($_POST['ingredient-unit']));
        
        $query  = "INSERT INTO ingredients (name, stock, unit) VALUES ('{$i_name}', '{$i_stock}', '{$i_unit}') ";
        $query .= "ON DUPLICATE KEY UPDATE stock=stock+{$i_stock}";
        
        if ($db->query($query)) {
            $session->msg('s', "Ingredient added successfully!");
            redirect('inventory.php', false);
        } else {
            $session->msg('d', 'Failed to add ingredient.');
            redirect('inventory.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('inventory.php', false);
    }
}

if (isset($_POST['restock_ingredient'])) {
    $i_id = (int) $_POST['ingredient-id'];
    $i_stock = (int) $_POST['restock-amount'];
    
    if ($i_id && $i_stock > 0) {
        $query = "UPDATE ingredients SET stock = stock + {$i_stock} WHERE id = '{$i_id}'";
        if ($db->query($query)) {
            $session->msg('s', "Ingredient restocked successfully!");
            redirect('inventory.php', false);
        } else {
            $session->msg('d', 'Failed to restock ingredient.');
            redirect('inventory.php', false);
        }
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
                <strong><span class="glyphicon glyphicon-list"></span> Ingredient Inventor
            </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Unit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_ingredients as $ingredient) : ?>
                        <tr>
                            <td><?php echo $ingredient['name']; ?></td>
                            <td><?php echo $ingredient['stock']; ?></td>
                            <td><?php echo $ingredient['unit']; ?></td>
                            <td>
                                <form method="post" action="inventory.php" style="display:inline;">
                                    <input type="hidden" name="ingredient-id" value="<?php echo (int)$ingredient['id']; ?>">
                                    <input type="number" name="restock-amount" placeholder="Amount" min="1" required>
                                    <button type="submit" name="restock_ingredient" class="btn btn-primary btn-xs">Restock</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-plus"></span> Add New Ingredient</strong>
            </div>
            <div class="panel-body">
                <form method="post" action="inventory.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="ingredient-name" placeholder="Ingredient Name" required>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="ingredient-stock" placeholder="Initial Stock" min="0" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ingredient-unit" required>
                            <option value="grams">Grams</option>
                            <option value="kilograms">Kilograms</option>
                            <option value="liters">Liters</option>
                            <option value="milliliters">Milliliters</option>
                            <option value="pieces">Pieces</option>
                        </select>
                    </div>
                    <button type="submit" name="add_ingredient" class="btn btn-success">Add Ingredient</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
