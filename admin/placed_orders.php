<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed orders section starts  -->

<section class="placed-orders">

   <h1 class="heading">Tất cả hóa đơn</h1>
   <div class="box-container" style="margin-bottom: 30px;">
      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['Đang xử lý']);
            $total_pendings = $select_pendings->rowCount();
         ?>
         <h1>Đơn hàng đang chờ: <?= $total_pendings; ?></h1>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['Đã hoàn thành']);
            $total_completes = $select_completes->rowCount();
         ?>
         <h1>Đơn hàng đã hoàn thành: <?= $total_completes; ?></h1>
      </div>

   </div>

   <div class="box-container">

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> Tên khách hàng : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> Ngày đặt : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
         <p> Số điện thoại : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> Địa chỉ : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> Tổng sản phẩm : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> Tổng tiền : <span><?= $fetch_orders['total_price']; ?>đ</span> </p>
         <p> Phương thức thanh toán : <span><?= $fetch_orders['method']; ?></span> </p>
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="payment_status" class="drop-down">
               <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="Đang xử lý">Đang xử lý</option>
               <option value="Đã hoàn thành">Đã hoàn thành</option>
            </select>
            <div class="flex-btn">
               <input type="submit" value="Cập nhật" class="btn" name="update_payment">
               <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('xóa hóa đơn này?');">Xóa</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">chưa có đơn hàng nào được tạo!</p>';
      }
      ?>

   </div>

</section>

<!-- placed orders section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>