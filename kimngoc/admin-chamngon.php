<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
};

if(isset($_POST['add_chamngon'])){

   $name = $_POST['name'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../mid_img/'.$image;

   $select_mid = $conn->prepare("SELECT * FROM `chamngon` WHERE name = ?");
   $select_mid->execute([$name]);

   if($select_mid->rowCount() > 0){
      $message[] = 'Câu nói đã tồn tại!';
   }else{
      if($image_size > 5000000){
         $message[] = 'Kích thước hình ảnh quá lớn';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `chamngon`(name, image) VALUES(?,?)");
         $insert_product->execute([$name, $image]);

         $message[] = 'Câu nói mới được thêm vào!';
      }

   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `chamngon` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../mid/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `chamngon` WHERE id = ?");
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

                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            <!-- ======================= Cards ================== -->
            <section class="add-products">

<form action="" method="POST" enctype="multipart/form-data">
   <h3>Thêm câu nói giới thiệu dsầu web</h3>
   <input type="text" required placeholder="i'm ..." name="name" class="box">
   <h4>Lưu ý: Hình ảnh thêm vào phải là ảnh nền trắng dưới định dạng .png </h4>
   <input type="file" name="image" class="box" accept="image/png" required>
   <input type="submit" value="Thêm câu nói" name="add_chamngon" class="btn">
</form>

</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

<div class="box-container">

<?php
   $show_mid = $conn->prepare("SELECT * FROM `chamngon` ORDER BY id DESC");
   $show_mid->execute();
   if($show_mid->rowCount() > 0){
      while($fetch_mid = $show_mid->fetch(PDO::FETCH_ASSOC)){  
?>
<div class="box">
   <img src="../mid_img/<?= $fetch_mid['image']; ?>" alt="">
   <div class="name">Câu nói: <h3><?= $fetch_mid['name']; ?></h3></div>
   <div class="flex-btn">
      <a href="update-mid?update=<?= $fetch_mid['id']; ?>" class="option-btn">Sửa</a>
      <a href="mid?delete=<?= $fetch_mid['id']; ?>" class="delete-btn" onclick="return confirm('Xóa tiêu đề?');">Xóa</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">Chưa có gì được thêm vào!</p>';
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