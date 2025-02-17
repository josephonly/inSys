<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(3);

if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id', 'quantity', 'price', 'total', 'date');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id    = $db->escape((int)$_POST['s_id']);
        $s_qty   = $db->escape((int)$_POST['quantity']);
        $s_total = $db->escape($_POST['total']);
        $s_date  = make_date();

        $sql  = "INSERT INTO sales (product_id, qty, price, date) VALUES ('{$p_id}', '{$s_qty}', '{$s_total}', '{$s_date}')";

        if ($db->query($sql)) {
            update_product_qty($s_qty, $p_id);
            $session->msg('s', "Sale added successfully.");
            redirect('add_sale.php', false);
        } else {
            $session->msg('d', 'Failed to add sale.');
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}

$products = find_all('products');
?>

<?php include_once('layouts/header.php'); ?>

<div class="container mt-4">
    <div class="row">
        <!-- Product List -->
        <div class="col-md-8">
            <h3>Choose Product</h3>
            <div class="row">
                <?php foreach ($products as $product) : ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                        <img class="card-img-top" src="uploads/products/<?php echo !empty($product['image']) ? $product['image'] : 'default.png'; ?>" alt="<?php echo $product['name']; ?>">

                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text">$<?php echo number_format($product['sale_price'], 2); ?></p>
                                <button class="btn btn-primary add-to-bill"
                                        data-id="<?php echo $product['id']; ?>"
                                        data-name="<?php echo $product['name']; ?>"
                                        data-price="<?php echo $product['sale_price']; ?>">
                                    Add to Bill
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bill Section -->
        <div class="col-md-4">
            <h3>Bill</h3>
            <div class="card">
                <div class="card-body">
                    <ul id="bill-items" class="list-group mb-3"></ul>
                    <h5>Total: $<span id="total-price">0.00</span></h5>
                    <button class="btn btn-success btn-block">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let total = 0;
    document.querySelectorAll('.add-to-bill').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            total += price;
            document.getElementById('total-price').innerText = total.toFixed(2);

            let billItem = document.createElement('li');
            billItem.className = 'list-group-item';
            billItem.innerText = name + " - $" + price.toFixed(2);
            document.getElementById('bill-items').appendChild(billItem);
        });
    });
</script>

<?php include_once('layouts/footer.php'); ?>
