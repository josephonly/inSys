<?php
  $page_title = 'User Profile';
  require_once('includes/load.php');
  page_require_level(3);

  $user_id = (int)$_GET['id'];
  if(empty($user_id)) {
    redirect('home.php', false);
  } else {
    $user_p = find_by_id('users', $user_id);
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-10 col-md-offset-1">
       <div class="panel panel-default">
           <div class="panel-heading">
               <h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> User Profile</h3>
           </div>
           <div class="panel-body">
               <table class="table table-bordered">
                   <thead>
                       <tr>
                           <th>#</th>
                           <th>Profile Image</th>
                           <th>Name</th>
                           <th>Username</th>
                           <th>User Role</th>
                           <th>Status</th>
                           <th>Actions</th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td><?php echo $user_p['id']; ?></td>
                           <td><img src="uploads/users/<?php echo $user_p['image']; ?>" class="img-thumbnail" width="50" height="50"></td>
                           <td><?php echo first_character($user_p['name']); ?></td>
                           <td><?php echo $user_p['username']; ?></td>
                           <td><?php echo $user_p['user_level'] == 1 ? 'Admin' : 'User'; ?></td>
                           <td><span class="label label-success">Active</span></td>
                           <td>
                               <a href="edit_account.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-pencil"></i></a>
                               <a href="delete_user.php?id=<?php echo $user_p['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');"><i class="glyphicon glyphicon-trash"></i></a>
                           </td>
                       </tr>
                   </tbody>
               </table>
           </div>
       </div>
   </div>
</div>
<?php include_once('layouts/footer.php'); ?>