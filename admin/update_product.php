<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $des = $_POST['des'];
   $des = filter_var($des, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ?, des = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $des, $pid]);

   $message[] = 'product updated!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $old_image2 = $_POST['old_image2'];
   $image2 = $_FILES['img_2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_STRING);
   $image2_size = $_FILES['img_2']['size'];
   $image2_tmp_name = $_FILES['img_2']['tmp_name'];
   $image2_folder = '../uploaded_img/'.$image2;

   $old_image3 = $_POST['old_image3'];
   $image3 = $_FILES['img_3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_STRING);
   $image3_size = $_FILES['img_3']['size'];
   $image3_tmp_name = $_FILES['img_3']['tmp_name'];
   $image3_folder = '../uploaded_img/'.$image3;

   if(!empty($image) || !empty($image2) || !empty($image3)){
      if($image_size > 2000000 || $image2_size > 2000000 || $image3_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ?, img_1 = ?, img_2 = ?, img_3 = ? WHERE id = ?");
         $update_image->execute([$image, $image, $image2, $image3,  $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         move_uploaded_file($image2_tmp_name, $image2_folder);
         move_uploaded_file($image3_tmp_name, $image3_folder);
         unlink('../uploaded_img/'.$old_image);
         unlink('../uploaded_img/'.$old_image2);
         unlink('../uploaded_img/'.$old_image3);
         $message[] = 'image updated!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">sửa thông tin sản phẩm</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
         <input type="hidden" name="old_image2" value="<?= $fetch_products['img_2']; ?>">
         <input type="hidden" name="old_image3" value="<?= $fetch_products['img_3']; ?>">

         <div class="image-container">
            <div class="main-image">
               <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="../uploaded_img/<?= $fetch_products['img_1']; ?>" alt="">
               <img src="../uploaded_img/<?= $fetch_products['img_2']; ?>" alt="">
               <img src="../uploaded_img/<?= $fetch_products['img_3']; ?>" alt="">
            </div>
         </div>
         <span>Cập nhật tên</span>
         <input type="text" required placeholder="Nhập tên sản phẩm" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
         <span>Cập nhật giá</span>
         <input type="text" min="0" max="9999999999" required placeholder="Nhập giá sản phẩm" name="price" class="box" value="<?= $fetch_products['price']; ?>">
         <span>Cập nhật loại sản phẩm</span>
         <select name="category" class="box" required>
            <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
            <option value="Apple">Apple</option>
            <option value="Samsung">Samsung</option>
            <option value="Xiaomi">Xiaomi</option>
            <option value="Nokia">Nokia</option>
         </select>
         <span>Cập nhật ảnh chính</span>
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
         <span>Cập nhật ảnh mô tả</span>
         <input type="file" name="img_2" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
         <input type="file" name="img_3" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
         <span>Cập nhật mô tả</span>
         <input type="text" required placeholder="Nhập mô tả sản phẩm" name="des" maxlength="225" class="box" value="<?= $fetch_products['des']; ?>">
         <div class="flex-btn">
            <input type="submit" value="Cập nhật" class="btn" name="update">
            <a href="products.php" class="option-btn">Trở lại</a>
         </div>
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">Không có sản phẩm nào</p>';
      }
   ?>

</section>

<!-- update product section ends -->










<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>