<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dairy Daily</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/css/login.css">  <!-- Link to External CSS -->
</head>
<body>

<div class="login-container">
    <img src="libs/images/logo.png" alt="Dairy Daily Logo" class="login-logo">  <!-- Add this line -->
    <h1>Point of Sale Syste</h1>
    
    <?php echo display_msg($msg); ?>
    <form method="post" action="auth_v2.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn btn-login">Login</button>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
