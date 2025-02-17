<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  page_require_level(3);

  if(isset($_POST['checkout'])){
    if(!empty($_POST['bill_data'])){
      $bill_data = json_decode($_POST['bill_data'], true);
      $sale_date = make_date();

      foreach ($bill_data as $item) {
        $p_id = $db->escape((int)$item['id']);
        $s_price = $db->escape($item['price']);
        $s_qty = 1; // Default quantity = 1
        $sql = "INSERT INTO sales (product_id, qty, price, date) 
                VALUES ('{$p_id}', '{$s_qty}', '{$s_price}', '{$sale_date}')";
        $db->query($sql);
        update_product_qty($s_qty, $p_id);
      }

      $session->msg('s', "Sale successfully added!");
      redirect('add_sale.php', false);
    } else {
      $session->msg('d', "No items in the bill!");
      redirect('add_sale.php', false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-8">
      <h3 class="mb-3">Choose Menu</h3>
      <input type="text" id="search-bar" class="form-control mb-3" placeholder="Search for items...">
      <div class="row" id="product-list">
        <?php 
          $products = find_all('products'); // Fetch products from database
          foreach ($products as $product): 
            if (!isset($product['price'])) continue; // Skip products without price
        ?>
        <div class="col-md-4 product-item">
          <div class="card mb-4">
            <img class="card-img-top" src="uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $product['name']; ?></h5>
              <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
              <button class="btn btn-primary add-to-bill" 
                      data-id="<?php echo $product['id']; ?>" 
                      data-name="<?php echo $product['name']; ?>" 
                      data-price="<?php echo $product['price']; ?>">
                Add to Bill
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-4">
      <h3 class="mb-3">Bill</h3>
      <div class="card">
        <div class="card-body">
          <form method="post" action="add_sale.php">
            <ul id="bill-items" class="list-group mb-3"></ul>
            <input type="hidden" name="bill_data" id="bill-data">
            <h5>Total: $<span id="total-price">0.00</span></h5>
            <button type="submit" name="checkout" class="btn btn-success btn-block">Checkout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let total = 0;
  let bill = [];

  document.querySelectorAll('.add-to-bill').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      const price = parseFloat(this.getAttribute('data-price'));

      total += price;
      document.getElementById('total-price').innerText = total.toFixed(2);

      let billItem = document.createElement('li');
      billItem.className = 'list-group-item';
      billItem.innerText = name + " - $" + price.toFixed(2);
      document.getElementById('bill-items').appendChild(billItem);

      bill.push({ id, name, price });
      document.getElementById('bill-data').value = JSON.stringify(bill);
    });
  });

  document.getElementById('search-bar').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
      const productName = item.querySelector('.card-title').innerText.toLowerCase();
      if (productName.includes(searchTerm)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });

  document.getElementById('product-list').addEventListener('click', function(event) {
    if (event.target.classList.contains('add-to-bill')) {
      const button = event.target;
      const id = button.getAttribute('data-id');
      const name = button.getAttribute('data-name');
      const price = parseFloat(button.getAttribute('data-price'));

      total += price;
      document.getElementById('total-price').innerText = total.toFixed(2);

      let billItem = document.createElement('li');
      billItem.className = 'list-group-item';
      billItem.innerText = name + " - $" + price.toFixed(2);
      document.getElementById('bill-items').appendChild(billItem);

      bill.push({ id, name, price });
      document.getElementById('bill-data').value = JSON.stringify(bill);
    }
  });
</script>

<?php include_once('layouts/footer.php'); ?>
