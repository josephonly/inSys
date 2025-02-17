<ul>
  <li>
    <a href="admin.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-user"></i>
      <span>User Management</span>
    </a>
    <ul class="nav submenu">
      <li><a href="group.php">Manage Groups</a> </li>
      <li><a href="users.php">Manage Users</a> </li>
    </ul>
  </li>
  <li>
    <a href="categorie.php">
      <i class="glyphicon glyphicon-indent-left"></i>
      <span>Categories</span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-large"></i>
      <span>Products</span>
    </a>
    <ul class="nav submenu">
      <li><a href="product.php">Manage Products</a> </li>
      <li><a href="add_product.php">Add Products</a> </li>
    </ul>
  </li>
  <li>
    <a href="media.php">
      <i class="glyphicon glyphicon-picture"></i>
      <span>Media Files</span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-credit-card"></i>
      <span>Sales</span>
    </a>
    <ul class="nav submenu">
      <li><a href="sales.php">Manage Sales</a> </li>
      <li><a href="add_sale.php">Add Sale</a> </li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-duplicate"></i>
      <span>Sales Report</span>
    </a>
    <ul class="nav submenu">
      <li><a href="sales_report.php">Sales by dates</a></li>
      <li><a href="monthly_sales.php">Monthly sales</a></li>
      <li><a href="daily_sales.php">Daily sales</a> </li>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-list-alt"></i>
      <span>Inventory</span>
    </a>
    <ul class="nav submenu">
      <li><a href="inventory.php">Manage Inventory</a> </li>
      <li><a href="add_inventory.php">Add Inventory</a> </li>
      <li><a href="inventory_report.php">Inventory Report</a> </li>
    </ul>
  </li>

  <!-- Date and Time Section -->
  <li style="position: fixed; bottom: 0; width: 14.4%; background-color: #f8f9fa; padding: 13px; border-top: 1px solid #ddd;">
    <span id="currentDateTime" style="font-size: 13px; font-weight: bold; font-family: Georgia, serif;"></span>
  </li>
</ul>

<script>
  function updateDateTime() {
    const now = new Date();
    const options = { 
      weekday: 'long', 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric', 
      hour: '2-digit', 
      minute: '2-digit',  
      hour12: true 
    };
    document.getElementById("currentDateTime").innerHTML = now.toLocaleString('en-US', options);
  }

  setInterval(updateDateTime, 1000); // Update every second
  updateDateTime(); // Run once immediately
</script>