<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $chitiet = $_POST['chitiet'];
   $chitiet = filter_var($chitiet, FILTER_SANITIZE_STRING);
   $loaisanpham = $_POST['loaisanpham'];
   $loaisanpham = filter_var($loaisanpham, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $fff = $_POST['fff'];
   $fff = filter_var($fff, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Tên sản phẩm đã tồn tại!';
   }else{
      if($image_size > 2000000){
         $message[] = 'Kích thước hình ảnh quá lớn'; 
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, fff, price, chitiet, loaisanpham, image) VALUES(?,?,?,?,?,?,?)");
         $insert_product->execute([$name, $category, $fff, $price, $chitiet, $loaisanpham, $image]);

         $message[] = 'Sản phẩm mới được thêm vào!';
      }

   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:dongthoigian');

}

?>


<!DOCTYPE html>
<html lang="en">

<?php include 'head-admin.php'; ?>

<body>
    <!-- =============== Navigation ================ -->
    <?php include 'lk/menu.php'; ?>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <!-- <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div> -->

                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <!-- ======================= Cards ================== -->
            <section class="add-products">

<form action="" method="POST" enctype="multipart/form-data">
   <h3>Thêm Dòng Thời Gian</h3>
   <input type="text" required placeholder="Tiêu Đề" name="name" maxlength="100" class="box">
   <input type="text" required placeholder="Năm" name="nam" maxlength="100" class="box">
   <input type="text" required placeholder="Tên địa điểm" name="namelink" maxlength="100" class="box">
   <input type="text" required placeholder="link web, không có bỏ trống" name="link" maxlength="100" class="box">
   <textarea name="text"></textarea>
   <script>
                        CKEDITOR.replace( 'text' );
                </script>

   
   </select>

   </select>
   <!-- <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required> -->
   <input type="submit" value="Thêm" name="add_product" class="btn">
</form>

</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

<div class="box-container">

<?php
   $show_products = $conn->prepare("SELECT * FROM `noidung` ORDER BY id DESC");
   $show_products->execute();
   if($show_products->rowCount() > 0){
      while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
?>
<div class="box">
   
   <div class="flex">
      
      
      <div class="name">Tiêu đề: <?= $fetch_products['name']; ?></div>
      <div class="name"><?= $fetch_products['nam']; ?></div>
      
   </div>
   <div class="name">Thứ tự hiện thị :<h3><?=$fetch_products['id']; ?></h3></div>
   <div class="name">Nội dung: <?= $fetch_products['text']; ?></div>
   <div class="flex-btn">
      <a href="update-dongthoigian?update=<?= $fetch_products['id']; ?>" class="option-btn">Sửa</a>
      <a href="dongthoigian?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm?');">Xóa</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">Chưa có sản phẩm được thêm vào!</p>';
   }
?>

</div>

</section>

            <!-- ================ Order Details List ================= -->
            
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>