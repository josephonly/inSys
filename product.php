<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add New</button>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Photo</th>
                <th> Product Title </th>
                <th class="text-center" style="width: 10%;"> Categories </th>
                <th class="text-center" style="width: 10%;"> Selling Price </th>
                <th class="text-center" style="width: 10%;"> Product Added </th>
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                <?php endif; ?>
                </td>
                <td> <?php echo remove_junk($product['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addProductModalLabel">Add New Product</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="add_product.php">
                    <div class="form-group">
                        <input type="text" class="form-control" name="product-title" placeholder="Product Title">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="product-categorie">
                            <option value="">Select Product Category</option>
                            <?php foreach ($all_categories as $cat) : ?>
                            <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="product-photo">
                            <option value="">Select Product Image</option>
                            <?php foreach ($all_photos as $photo) : ?>
                            <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                    </div>
                    <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <?php include_once('layouts/footer.php'); ?>