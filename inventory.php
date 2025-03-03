<?php
$page_title = 'Ingredient Inventory';
require_once('includes/load.php');
page_require_level(2);

$all_ingredients = find_all('ingredients');

/* Nagdagdag ako ng security na hindi nakakapag add ng existing ingredient*/
if (isset($_POST['add_ingredient'])) {
    $req_fields = array('ingredient-name', 'ingredient-stock', 'ingredient-unit');
    validate_fields($req_fields);

    if (empty($errors)) {
        $i_name  = remove_junk($db->escape($_POST['ingredient-name']));
        $i_stock = (int) $_POST['ingredient-stock'];
        $i_unit  = remove_junk($db->escape($_POST['ingredient-unit']));

        // Check if the ingredient name already exists
        $check_query = "SELECT id FROM ingredients WHERE name = '{$i_name}' LIMIT 1";
        $result = $db->query($check_query);

        if ($db->num_rows($result) > 0) {
            // Ingredient already exists
            $session->msg('d', "The ingredient '{$i_name}' already exists in the database.");
            redirect('inventory.php', false);
        } else {
            // Insert the new ingredient
            $query  = "INSERT INTO ingredients (name, stock, unit) VALUES ('{$i_name}', '{$i_stock}', '{$i_unit}')";

            if ($db->query($query)) {
                $session->msg('s', "Ingredient added successfully!");
                redirect('inventory.php', false);
            } else {
                $session->msg('d', 'Failed to add ingredient.');
                redirect('inventory.php', false);
            }
        }
    } else {
        $session->msg("d", $errors);
        redirect('inventory.php', false);
    }
}


/* nilagyan ko ng unit para maidentify kung ano yung nilalagay at converted na sa g -> kg and mL -> L */
if (isset($_POST['restock_ingredient'])) {
    $i_id = (int) $_POST['ingredient-id'];
    $i_stock = (float) $_POST['restock-amount'];
    $i_unit = (string) $_POST['ingredient-unit'];

    if ($i_id && $i_stock > 0) {
        // Convert grams and milliliters to kilograms and liters respectively
        if ($i_unit == 'grams') {
            $f_stock = $i_stock / 1000; // Convert grams to kilograms
        } elseif ($i_unit == 'milliliters') {
            $f_stock = $i_stock / 1000; // Convert milliliters to liters
        } else {
            // Keep the stock unchanged for kilograms, liters, and pieces
            $f_stock = $i_stock;
        }

        // Update the ingredient stock
        $query = "UPDATE ingredients SET stock = stock + {$f_stock} WHERE id = '{$i_id}'";
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
                <strong><span class="glyphicon glyphicon-list"></span> Ingredient Inventory
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

                                        <select name="ingredient-unit" required>
                                            <?php
                                            $unit = $ingredient['unit']; // Get the unit of the ingredient

                                            if ($unit === "kilograms") {
                                                echo '<option value="grams">Grams</option>';
                                                echo '<option value="kilograms">Kilograms</option>';
                                            } elseif ($unit === "liters") {
                                                echo '<option value="milliliters">Milliliters</option>';
                                                echo '<option value="liters">Liters</option>';
                                            } else {
                                                echo '<option value="' . $unit . '">' . ucfirst($unit) . '</option>';
                                            }
                                            ?>
                                        </select>

                                        <button type="submit" name="restock_ingredient" class="btn btn-primary btn-xs">Add Stock</button>
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
                    <!-- Inalis ko na yung grams at milliliters dito sa add ingredient -->
                    <div class="form-group">
                        <select class="form-control" name="ingredient-unit" required>
                            <option value="kilograms">Kilograms</option>
                            <option value="liters">Liters</option>
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