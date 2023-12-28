<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $image2 = $_FILES['img_2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_STRING);
   $image2_size = $_FILES['img_2']['size'];
   $image2_tmp_name = $_FILES['img_2']['tmp_name'];
   $image2_folder = '../uploaded_img/'.$image2;

   $image3 = $_FILES['img_3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_STRING);
   $image3_size = $_FILES['img_3']['size'];
   $image3_tmp_name = $_FILES['img_3']['tmp_name'];
   $image3_folder = '../uploaded_img/'.$image3;

   $des = $_POST['des'];
   $des = filter_var($des, FILTER_SANITIZE_STRING);

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exists!';
   }else{
      if($image_size > 2000000 || $image2_size > 2000000 || $image3_size > 2000000){
         $message[] = 'image size is too large';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
         move_uploaded_file($image2_tmp_name, $image2_folder);
         move_uploaded_file($image3_tmp_name, $image3_folder);

         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image, img_1, img_2, img_3, des) VALUES(?,?,?,?,?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image, $image, $image2, $image3, $des]);

         $message[] = 'new product added!';
         
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
   <title>Products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Thêm sản phẩm</h3>
      <input type="text" required placeholder="Nhập tên sản phẩm" name="name" maxlength="100" class="box">
      <input type="text" min="0" max="9999999999" required placeholder="Nhập giá sản phẩm" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Chọn loại --</option>
         <option value="Apple">Apple</option>
         <option value="Samsung">Samsung</option>
         <option value="Xiaomi">Xiaomi</option>
         <option value="Nokia">Nokia</option>
      </select>
      <h2 style="float: left;">Ảnh chính</h2>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <h2 style="float: left;">Ảnh mô tả</h2>
      <input type="file" name="img_2" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <h2 style="float: left;">Ảnh mô tả</h2>
      <input type="file" name="img_3" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="text" required placeholder="Nhập mô tả sản phẩm" name="des" maxlength="255" class="box">
      <div class="flex-btn">
         <input type="submit" value="Thêm sản phẩm" name="add_product" class="btn">
         <a href="products.php" class="option-btn">Trở lại</a>
      </div>
      
   </form>

</section>

<!-- add products section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>