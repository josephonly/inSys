<?php
  $page_title = 'Manage Users';
  require_once('includes/load.php');
  page_require_level(1);
  $all_users = find_all('users');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-user"></span>
          <span>Users</span>
        </strong>
        <!-- Add New User Button -->
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addUserModal">
          <i class="glyphicon glyphicon-plus"></i> Add New User
        </button>
      </div>

      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Name</th>
              <th class="text-center">Username</th>
              <th class="text-center">User Role</th>
              <th class="text-center">Status</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="userTableBody">
            <?php foreach($all_users as $user): ?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk(ucwords($user['name']))?></td>
                <td class="text-center"><?php echo remove_junk($user['username'])?></td>
                <td class="text-center"><?php echo remove_junk($user['user_level'])?></td>
                <td class="text-center">
                  <?php if($user['status'] === '1'): ?>
                    <span class="label label-success">Active</span>
                  <?php else: ?>
                    <span class="label label-danger">Deactive</span>
                  <?php endif;?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_user.php?id=<?php echo (int)$user['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                      <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                    <a href="delete_user.php?id=<?php echo (int)$user['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                      <i class="glyphicon glyphicon-remove"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New User</h4>
      </div>
      <div class="modal-body">
        <form id="addUserForm">
          <div class="form-group">
              <label for="name" class="control-label">Full Name</label>
              <input type="text" class="form-control" name="name" required>
          </div>
          <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="text" class="form-control" name="username" required>
          </div>
          <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <input type="password" class="form-control" name="password" required>
          </div>
          <div class="form-group">
              <label for="user_level">User Role</label>
              <select class="form-control" name="user_level">
                <option value="1">Admin</option>
                <option value="2">Editor</option>
                <option value="3">User</option>
              </select>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
              <select class="form-control" name="status">
                <option value="1">Active</option>
                <option value="0">Deactive</option>
              </select>
          </div>
          <button type="submit" class="btn btn-success">Save User</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $("#addUserForm").submit(function(e){
    e.preventDefault(); // Prevent form from reloading the page

    $.ajax({
      type: "POST",
      url: "add_user.php",
      data: $(this).serialize() + "&ajax=1",
      dataType: "json",
      success: function(response){
        if(response.status == "success") {
          // Append new row in table
          $("#userTableBody").append(`
            <tr>
              <td class="text-center">${response.user.id}</td>
              <td>${response.user.name}</td>
              <td class="text-center">${response.user.username}</td>
              <td class="text-center">${response.user.user_level}</td>
              <td class="text-center">${response.user.status}</td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_user.php?id=${response.user.id}" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                    <i class="glyphicon glyphicon-pencil"></i>
                  </a>
                  <a href="delete_user.php?id=${response.user.id}" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>
                </div>
              </td>
            </tr>
          `);
          
          // Close modal
          $("#addUserModal").modal("hide");
          
          // Reset form fields
          $("#addUserForm")[0].reset();
        } else {
          alert("Error: " + response.message);
        }
      }
    });
  });
});
</script>

<?php include_once('layouts/footer.php'); ?>
