<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
};

if(isset($_POST['add_congviec'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $text = $_POST['text'];
   $text = filter_var($text, FILTER_SANITIZE_STRING);

   $select_congviec = $conn->prepare("SELECT * FROM `congviec` WHERE name = ?");
   $select_congviec->execute([$name]);

   if($select_congviec->rowCount() > 0){
      $message[] = 'Tên Công việc đã tồn tại!';
   }else{

         $insert_congviec = $conn->prepare("INSERT INTO `congviec`(name, text) VALUES(?,?)");
         $insert_congviec->execute([$name, $text]);

         $message[] = 'Công việc mới được thêm vào!';
      }
   }




if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_congviec_image = $conn->prepare("SELECT * FROM `congviec` WHERE id = ?");
   $delete_congviec_image->execute([$delete_id]);
   $delete_congviec = $conn->prepare("DELETE FROM `congviec` WHERE id = ?");
   $delete_congviec->execute([$delete_id]);

   header('location:congviec');

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

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Thêm Công Việc</h3>
      <input type="text" required placeholder="Tên Công Việc" name="name" maxlength="100" class="box">
      <textarea name="text"></textarea>
      <script>
               CKEDITOR.replace( 'text' );
      </script>
      
      <input type="submit" value="Thêm công việc" name="add_congviec" class="btn">
   </form>

</section>

<!-- add congviec section ends -->

<!-- show congviec section starts  -->

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
      $show_congviec = $conn->prepare("SELECT * FROM `congviec`");
      $show_congviec->execute();
      if($show_congviec->rowCount() > 0){
         while($fetch_congviec = $show_congviec->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      
      <div class="name">Thứ tự hiện thị :<h3><?= $fetch_congviec['id']; ?></h3></div>
      <div class="name">Tên danh mục :<h3><?= $fetch_congviec['name']; ?></h3></div>
      <div class="name">Nội Dung :<h3><?= $fetch_congviec['text']; ?></h3></div>
      <div class="flex-btn">
         <a href="update-congviec?update=<?= $fetch_congviec['id']; ?>" class="option-btn">Sửa</a>
         <a href="congviec?delete=<?= $fetch_congviec['id']; ?>" class="delete-btn" onclick="return confirm('Xóa công việc?');">Xóa</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Chưa có công việc được thêm vào!</p>';
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