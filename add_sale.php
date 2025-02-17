<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id', 'quantity', 'price', 'total', 'date');
    validate_fields($req_fields);

    if(empty($errors)){
        $p_id    = $db->escape((int)$_POST['s_id']);
        $s_qty   = $db->escape((int)$_POST['quantity']);
        $s_price = $db->escape($_POST['price']);
        $s_total = $db->escape($_POST['total']);
        $s_date  = $db->escape($_POST['date']);

        // Check stock availability
        $stock_query = $db->query("SELECT quantity FROM products WHERE id = '{$p_id}' LIMIT 1");
        $stock = $db->fetch_assoc($stock_query);

        if ($stock && $stock['quantity'] >= $s_qty) {
            $sql = "INSERT INTO sales (product_id, qty, price, total, date) VALUES ('{$p_id}', '{$s_qty}', '{$s_price}', '{$s_total}', '{$s_date}')";
            
            if($db->query($sql)){
                update_product_qty($s_qty, $p_id);  // Deduct stock
                $session->msg('s', "Sale added successfully.");
                redirect('add_sale.php', false);
            } else {
                $session->msg('d', 'Failed to add sale.');
                redirect('add_sale.php', false);
            }
        } else {
            $session->msg('d', 'Not enough stock available.');
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="add_sale.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find It</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title" placeholder="Search for product name">
          </div>
          <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
          <table class="table table-bordered">
            <thead>
              <th> Item </th>
              <th> Price </th>
              <th> Qty </th>
              <th> Total </th>
              <th> Date</th>
              <th> Action</th>
            </thead>
            <tbody id="product_info"></tbody>
          </table>
          <button type="submit" name="add_sale" class="btn btn-success">Add Sale</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
