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
   document.addEventListener("DOMContentLoaded", function() {
    let total = 0;
    
    // Function: Update Total
    function updateTotal() {
        let totalPrice = 0;
        document.querySelectorAll(".bill-item").forEach(item => {
            let price = parseFloat(item.dataset.price);
            let quantity = parseInt(item.querySelector(".item-quantity").value);
            let itemTotal = price * quantity;
            item.querySelector(".item-total").innerText = itemTotal.toFixed(2);
            totalPrice += itemTotal;
        });
        total = totalPrice;
        document.getElementById("total-price").innerText = total.toFixed(2);
        updateChange();
    }

    // Function: Update Change
    function updateChange() {
        let customerMoney = parseFloat(document.getElementById("customer-money").value) || 0;
        let change = customerMoney - total;
        document.getElementById("change-amount").innerText = change >= 0 ? change.toFixed(2) : "0.00";
    }

    // Add to Bill
    document.querySelectorAll(".add-to-bill").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);
            const imagePath = this.dataset.image;

            let existingItem = document.querySelector(`#bill-items .bill-item[data-id="${id}"]`);
            if (existingItem) {
                let quantityInput = existingItem.querySelector(".item-quantity");
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updateTotal();
            } else {
                let billItem = document.createElement("tr");
                billItem.className = "bill-item";
                billItem.dataset.id = id;
                billItem.dataset.price = price;
                billItem.innerHTML = `
                    <td><img src="${imagePath}" class="w-10 h-10 rounded-full"> ${name}</td>
                    <td>$${price.toFixed(2)}</td>
                    <td><input type="number" class="item-quantity w-12 border rounded text-center" value="1" min="1"></td>
                    <td>$<span class="item-total">${price.toFixed(2)}</span></td>
                    <td><button class="remove-item bg-red-500 text-white px-2 py-1 rounded">X</button></td>
                `;

                document.getElementById("bill-items").appendChild(billItem);

                // Add Event Listeners to new items
                billItem.querySelector(".item-quantity").addEventListener("change", updateTotal);
                billItem.querySelector(".remove-item").addEventListener("click", function() {
                    billItem.remove();
                    updateTotal();
                });
            }

            updateTotal();
        });
    });

    // Customer Money Input Event
    document.getElementById("customer-money").addEventListener("input", updateChange);

    // Checkout Button
    document.getElementById("checkout-button").addEventListener("click", function() {
        if (total === 0) {
            alert("No items in the bill!");
            return;
        }

        let customerMoney = parseFloat(document.getElementById("customer-money").value) || 0;
        if (customerMoney < total) {
            alert("Not enough money!");
            return;
        }

        alert("Payment successful! Change: $" + (customerMoney - total).toFixed(2));

        // Reset Bill
        document.getElementById("bill-items").innerHTML = "";
        document.getElementById("total-price").innerText = "0.00";
        document.getElementById("customer-money").value = "";
        document.getElementById("change-amount").innerText = "0.00";
        total = 0;
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