<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(3);

$products = find_all('products');
$categories = find_all('categories');
?>

<?php include_once('layouts/header.php'); ?>

<!-- Add Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="max-w-7xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Product Section -->
        <div>
            <h3 class="text-2xl font-bold mb-4">Choose Product</h3>
            <div class="mb-4">
                <select id="category-filter" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($products as $product) : ?>
                    <?php
                    $image = find_by_id('media', $product['media_id']);
                    $image_path = $image ? "uploads/products/" . $image['file_name'] : "uploads/no_image.png";
                    ?>
                    <div class="bg-white p-4 rounded-lg shadow-md hover:scale-105 transition transform duration-300">
                        <img src="<?php echo htmlspecialchars($image_path); ?>" 
                             class="w-full h-40 object-cover rounded-lg">
                        <h5 class="text-lg font-semibold mt-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="text-gray-600">$<?php echo number_format($product['sale_price'], 2); ?></p>
                        <button class="mt-3 w-full bg-indigo-500 text-white py-2 rounded-lg hover:bg-indigo-600 transition add-to-bill"
                                data-id="<?php echo htmlspecialchars($product['id']); ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo htmlspecialchars($product['sale_price']); ?>"
                                data-image="<?php echo htmlspecialchars($image_path); ?>">
                            Add to Bill
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bill Section -->
        <div class="bg-gray-100 p-6 rounded-xl shadow-md">
            <h3 class="text-2xl font-bold mb-4">Bill</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-2">Product</th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="bill-items" class="text-gray-700">
                    <!-- Dynamic Bill Items -->
                </tbody>
            </table>
            
            <h5 class="text-xl font-bold mt-4">Total: $<span id="total-price">0.00</span></h5>
            
            <div class="mt-3">
                <label for="customer-money" class="block font-semibold">Customer Money:</label>
                <input type="number" id="customer-money" class="w-full p-2 border rounded-lg" placeholder="Enter amount">
            </div>
            
            <h5 class="text-lg font-bold mt-2">Change: $<span id="change-amount">0.00</span></h5>
            
            <button id="checkout-button" class="w-full bg-green-500 text-white py-2 mt-4 rounded-lg hover:bg-green-600 transition">
                Checkout
            </button>
        </div>

    </div>
</div>

<script>
    let total = 0;

    // Category filter
    document.getElementById('category-filter').addEventListener('change', function() {
        let selectedCategory = this.value;
        document.querySelectorAll('.product-item').forEach(item => {
            if (selectedCategory === 'all' || item.getAttribute('data-category') === selectedCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Add to bill functionality
    document.querySelectorAll('.add-to-bill').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const imagePath = this.getAttribute('data-image');

            let existingItem = document.querySelector(`#bill-items .bill-item[data-id="${id}"]`);
            if (existingItem) {
                let quantityInput = existingItem.querySelector('.item-quantity');
                let currentQuantity = parseInt(quantityInput.value);
                currentQuantity++;
                quantityInput.value = currentQuantity;

                const itemTotal = price * currentQuantity;
                existingItem.querySelector('.item-total').innerText = itemTotal.toFixed(2);
                total += price;
                document.getElementById('total-price').innerText = total.toFixed(2);
            } else {
                const quantity = 1;
                const itemTotal = price * quantity;
                total += itemTotal;
                document.getElementById('total-price').innerText = total.toFixed(2);

                let billItem = document.createElement('tr');
                billItem.className = 'bill-item';
                billItem.setAttribute('data-id', id);
                billItem.innerHTML = `
                    <td><img src="${imagePath}" class="w-10 h-10 rounded-full"> ${name}</td>
                    <td>$${price.toFixed(2)}</td>
                    <td><input type="number" class="item-quantity w-12 border rounded" value="${quantity}" min="1"></td>
                    <td>$<span class="item-total">${itemTotal.toFixed(2)}</span></td>
                    <td><button class="bg-red-500 text-white px-2 py-1 rounded remove-item">X</button></td>
                `;

                document.getElementById('bill-items').appendChild(billItem);
            }
        });
    });
</script>

<?php include_once('layouts/footer.php'); ?>
