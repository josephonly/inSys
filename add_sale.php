<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(3);

$products = find_all('products');
?>

<?php include_once('layouts/header.php'); ?>

<!-- Link to the external CSS file -->
<link rel="stylesheet" href="libs/css/add_sale.css">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h3>Choose Product</h3>
            <div class="row">
                <?php foreach ($products as $product) : ?>
                    <?php
                    // Get product image
                    $image = find_by_id('media', $product['media_id']);
                    $image_path = $image ? "uploads/products/" . $image['file_name'] : "uploads/no_image.png";
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img class="card-img-top" src="<?php echo $image_path; ?>" alt="<?php echo $product['name']; ?>">
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
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
           
            document.getElementById('total-price').innerText = total.toFixed(2);

            let billItem = document.createElement('li');
            billItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            billItem.innerHTML = `${name} - $${price.toFixed(2)} x ${quantity} = $${itemTotal.toFixed(2)}` + 
                `<button class="btn btn-danger btn-sm cancel-item" data-id="${id}" data-price="${itemTotal}">Cancel</button>`;
            document.getElementById('bill-items').appendChild(billItem);

            billItem.querySelector('.cancel-item').addEventListener('click', function() {
                const itemPrice = parseFloat(this.getAttribute('data-price'));
                total -= itemPrice;
                document.getElementById('total-price').innerText = total.toFixed(2);
                this.parentElement.remove();
            });
        });
    });
</script>

<?php include_once('layouts/footer.php'); ?>