<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(3);

// Fetch products and categories
$products = find_all('products');
$categories = find_all('categories');
?>

<?php include_once('layouts/header.php'); ?>

<!-- Link to the external CSS file -->
<link rel="stylesheet" href="libs/css/add_sale.css">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h3>Choose Product</h3>
            
            <!-- Category Filter Dropdown -->
            <select id="category-filter" class="form-control mb-3">
                <option value="all">All Categories</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <div class="row" id="product-list">
                <?php foreach ($products as $product) : ?>
                    <?php
                    // Get product image
                    $image = find_by_id('media', $product['media_id']);
                    $image_path = $image ? "uploads/products/" . $image['file_name'] : "uploads/no_image.png";
                    ?>
                    <div class="col-md-4 product-item" data-category="<?php echo htmlspecialchars($product['categorie_id']); ?>">
                        <div class="card mb-4">
                            <img class="card-img-top" src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text">$<?php echo number_format($product['sale_price'], 2); ?></p>
                                <button class="btn btn-primary add-to-bill"
                                        data-id="<?php echo htmlspecialchars($product['id']); ?>"
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                        data-price="<?php echo htmlspecialchars($product['sale_price']); ?>">
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
                    <div class="form-group">
                        <label for="customer-money">Customer Money:</label>
                        <input type="number" id="customer-money" class="form-control" placeholder="Enter amount">
                    </div>
                    <h5>Change: $<span id="change-amount">0.00</span></h5>
                    <button class="btn btn-success btn-block" id="checkout-button">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Category filter functionality
    document.getElementById('category-filter').addEventListener('change', function() {
        let selectedCategory = this.value;
        document.querySelectorAll('.product-item').forEach(item => {
            // Show products that match the selected category or show all
            if (selectedCategory === 'all' || item.getAttribute('data-category') === selectedCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    let total = 0;

    // Add product to bill
    document.querySelectorAll('.add-to-bill').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const quantity = 1;

            const itemTotal = price * quantity;
            total += itemTotal;
            document.getElementById('total-price').innerText = total.toFixed(2);

            let billItem = document.createElement('li');
            billItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            billItem.innerHTML = `
    ${name} - $${price.toFixed(2)} x <span class="item-quantity">${quantity}</span> = $<span class="item-total">${itemTotal.toFixed(2)}</span>
    <div class="btn-group" role="group">
        <button class="btn btn-secondary btn-sm decrease-quantity" data-id="${id}" data-price="${price}">-</button>
        <button class="btn btn-secondary btn-sm increase-quantity" data-id="${id}" data-price="${price}">+</button>
        <button class="btn btn-danger btn-sm cancel-item" data-id="${id}" data-price="${itemTotal}">Cancel</button>
    </div>
`;


            document.getElementById('bill-items').appendChild(billItem);

            billItem.querySelector('.cancel-item').addEventListener('click', function() {
    const itemPrice = parseFloat(this.getAttribute('data-price'));
    total -= itemPrice;
    document.getElementById('total-price').innerText = total.toFixed(2);
    billItem.remove(); // Corrected to remove the entire bill item
});


            billItem.querySelector('.decrease-quantity').addEventListener('click', function() {
                let currentQuantity = parseInt(billItem.querySelector('.item-quantity').innerText);
                if (currentQuantity > 1) {
                    currentQuantity--;
                    const newItemTotal = price * currentQuantity;
                    total -= price;
                    document.getElementById('total-price').innerText = total.toFixed(2);

                    billItem.querySelector('.item-quantity').innerText = currentQuantity;
                    billItem.querySelector('.item-total').innerText = newItemTotal.toFixed(2);
                }
            });

            billItem.querySelector('.increase-quantity').addEventListener('click', function() {
                let currentQuantity = parseInt(billItem.querySelector('.item-quantity').innerText);
                currentQuantity++;
                const newItemTotal = price * currentQuantity;
                total += price;
                document.getElementById('total-price').innerText = total.toFixed(2);

                billItem.querySelector('.item-quantity').innerText = currentQuantity;
                billItem.querySelector('.item-total').innerText = newItemTotal.toFixed(2);
            });
        });
    });

    // Update change amount based on customer money input
    document.getElementById('customer-money').addEventListener('input', function() {
        const customerMoney = parseFloat(this.value);
        const change = customerMoney - total;
        document.getElementById('change-amount').innerText = change.toFixed(2);
    });

    // Checkout functionality
    document.getElementById('checkout-button').addEventListener('click', function() {
        const customerMoney = parseFloat(document.getElementById('customer-money').value);
        if (customerMoney < total) {
            alert('Insufficient funds!');
        } else {
            alert('Transaction successful!');
            // Reset the bill and total
            document.getElementById('bill-items').innerHTML = '';
            total = 0;
            document.getElementById('total-price').innerText = '0.00';
            document.getElementById('customer-money').value = '';
            document.getElementById('change-amount').innerText = '0.00';
        }
    });
</script>

<?php include_once('layouts/footer.php'); ?>
