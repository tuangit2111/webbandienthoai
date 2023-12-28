<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h2 class="heading">Chào mừng <?= $fetch_profile['name']; ?>!</h2>
   <div class="box-container">
      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3>Tổng đơn hàng: <?= $numbers_of_orders; ?></h3>
         <a href="placed_orders.php" class="btn">Xem tất cả</a>
      </div>
      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         ?>
         <h3>Số sản phẩm hiện có: <?= $numbers_of_products; ?></h3>
         <a href="products.php" class="btn">Xem tất cả</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $numbers_of_users = $select_users->rowCount();
         ?>
         <h3>Số tài khoản user: <?= $numbers_of_users; ?></h3>
         <a href="users_accounts.php" class="btn">Xem tất cả</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admin`");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->rowCount();
         ?>
         <h3>Số tài khoản admin: <?= $numbers_of_admins; ?></h3>
         <a href="admin_accounts.php" class="btn">Xem tất cả</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $numbers_of_messages = $select_messages->rowCount();
         ?>
         <h3>Hộp thư đến: <?= $numbers_of_messages; ?></h3>
         <a href="messages.php" class="btn">Xem tất cả</a>
      </div>

   </div>
   <div class="box-container">
      <div class="box" style="width: 25%; height: 50px">
         <?php
            $total = 0;
            $select = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select->execute(['Đã hoàn thành']);
            while($fetch_completes = $select->fetch(PDO::FETCH_ASSOC)){
               $total += $fetch_completes['total_price'];
            }
         ?>
         <h1 style="float: left;">Doanh thu cửa hàng: <?= $total; ?> VND</h1>
      </div>
   </div>

</section>

<!-- admin dashboard section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>