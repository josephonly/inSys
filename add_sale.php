<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(3);

// Fetch products and categories
$products = find_all('products');
$categories = find_all('categories');
?>

<?php include_once('layouts/header.php'); ?>

<!-- Link to the custom CSS file -->
<link rel="stylesheet" href="libs/css/add_sale.css">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <h3>Choose Product</h3>
            
            <!-- Category Filter Dropdown -->
            <div class="select-category-container"> <!-- Category Box Wrapper -->
                <select id="category-filter" class="form-control mb-3">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="row product-grid" id="product-list"> <!-- Product Box Wrapper -->
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
                                        data-price="<?php echo htmlspecialchars($product['sale_price']); ?>"
                                        data-image="<?php echo htmlspecialchars($image_path); ?>"> <!-- Added image data -->
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
                    <!-- Custom Table for Bill -->
                    <table id="bill-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bill-items">
                            <!-- Bill items will be appended here -->
                        </tbody>
                    </table>
                    <h5>Total: $<span id="total-price">0.00</span></h5>
                    <div class="form-group">
                        <label for="customer-money">Customer Money:</label>
                        <input type="number" id="customer-money" class="form-control" placeholder="Enter amount">
                    </div>
                    <h5>Change: $<span id="change-amount">0.00</span></h5>
                    <button class="btn btn-success" id="checkout-button">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let total = 0;

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

    // Add product to bill
    document.querySelectorAll('.add-to-bill').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const imagePath = this.getAttribute('data-image');  // Get image path

            // Check if product already exists in the bill
            let existingItem = document.querySelector(`#bill-items .bill-item[data-id="${id}"]`);
            if (existingItem) {
                // If product exists, allow quantity change
                let quantityInput = existingItem.querySelector('.item-quantity');
                let currentQuantity = parseInt(quantityInput.value);
                currentQuantity++;
                quantityInput.value = currentQuantity;
                
                // Update total
                const itemTotal = price * currentQuantity;
                existingItem.querySelector('.item-total').innerText = itemTotal.toFixed(2);
                total += price;
                document.getElementById('total-price').innerText = total.toFixed(2);
            } else {
                // If product does not exist, add it to the bill
                const quantity = 1;
                const itemTotal = price * quantity;
                total += itemTotal;
                document.getElementById('total-price').innerText = total.toFixed(2);

                let billItem = document.createElement('tr');
                billItem.className = 'bill-item';
                billItem.setAttribute('data-id', id);
                billItem.innerHTML = `
                    <td><img src="${imagePath}" alt="${name}" width="50" height="50"> ${name}</td>  <!-- Added image -->
                    <td>$${price.toFixed(2)}</td>
                    <td><input type="number" class="item-quantity" value="${quantity}" min="1"></td>
                    <td>$<span class="item-total">${itemTotal.toFixed(2)}</span></td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-danger btn-sm cancel-item" data-id="${id}" data-price="${itemTotal}">Cancel</button>
                        </div>
                    </td>
                `;

                document.getElementById('bill-items').appendChild(billItem);

                // Event listener for canceling the item
                billItem.querySelector('.cancel-item').addEventListener('click', function() {
                    const itemPrice = parseFloat(this.getAttribute('data-price'));
                    total -= itemPrice;
                    document.getElementById('total-price').innerText = total.toFixed(2);
                    billItem.remove(); // Remove the entire bill item
                });

                // Update total when quantity changes
                billItem.querySelector('.item-quantity').addEventListener('input', function() {
                    let currentQuantity = parseInt(this.value);
                    if (currentQuantity < 1) {
                        this.value = 1;
                        currentQuantity = 1;
                    }
                    const newItemTotal = price * currentQuantity;
                    total += price * (currentQuantity - 1); // Adjust total for quantity change
                    document.getElementById('total-price').innerText = total.toFixed(2);
                    billItem.querySelector('.item-total').innerText = newItemTotal.toFixed(2);
                });
            }
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
