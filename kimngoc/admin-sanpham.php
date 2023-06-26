<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
};

if(isset($_POST['add_sanpham'])){

   $name = $_POST['name'];
   $text1 = $_POST['text1'];
   $text2 = $_POST['text2'];
   $text3 = $_POST['text3'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../mid_img/'.$image;

   $select_mid = $conn->prepare("SELECT * FROM `sanpham` WHERE name = ?");
   $select_mid->execute([$name]);

   if($select_mid->rowCount() > 0){
      $message[] = 'Sản phẩm đã tồn tại!';
   }else{
      if($image_size > 5000000){
         $message[] = 'Kích thước hình ảnh quá lớn';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `sanpham`(name, text1, text2, text3, image) VALUES(?,?,?,?,?)");
         $insert_product->execute([$name, $text1, $text2, $text3, $image]);

         $message[] = 'Sản phẩm mới được thêm vào!';
      }

   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../mid/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `sanpham` WHERE id = ?");
   $delete_product->execute([$delete_id]);

   header('location:mid');

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
   <h3>Thêm sản phẩm</h3>
   <input type="text" required placeholder="i'm ..." name="name" class="box">
   <input type="text" required placeholder="Text 1" name="text1" class="box">
   <input type="text" required placeholder="Text 2" name="text2" class="box">
   <input type="text" required placeholder="Text 3" name="text3" class="box">
   <h4>Lưu ý: Hình ảnh thêm vào phải là ảnh nền trắng dưới định dạng .png </h4>
   <input type="file" name="image" class="box" accept="image/png" required>
   <input type="submit" value="Thêm " name="add_sanpham" class="btn">
</form>

</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

<div class="box-container">

<?php
   $show_mid = $conn->prepare("SELECT * FROM `sanpham` ORDER BY id DESC");
   $show_mid->execute();
   if($show_mid->rowCount() > 0){
      while($fetch_mid = $show_mid->fetch(PDO::FETCH_ASSOC)){  
?>
<div class="box">
   <img src="../mid_img/<?= $fetch_mid['image']; ?>" alt="">
   <div class="name">Tên sản phẩm :<h3><?= $fetch_mid['name']; ?></h3></div>
   <div class="name">Text 1 :<h5><?= $fetch_mid['text1']; ?></h5></div>
   <div class="name">Text 2 :<h5><?= $fetch_mid['text2']; ?></h5></div>
   <div class="name">Text 3 :<h5><?= $fetch_mid['text3']; ?></h5></div>
   <div class="flex-btn">
      <a href="update-sanpham?update=<?= $fetch_mid['id']; ?>" class="option-btn">Sửa</a>
      <a href="mid?delete=<?= $fetch_mid['id']; ?>" class="delete-btn" onclick="return confirm('Xóa ?');">Xóa</a>
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

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>