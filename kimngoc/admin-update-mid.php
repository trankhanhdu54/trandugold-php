<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
};

if(isset($_POST['update'])){

   $id = $_POST['id'];
   $id = filter_var($id, FILTER_SANITIZE_STRING);
   $xid = $_POST['xid'];
   $xid = filter_var($xid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $text1 = $_POST['text1'];
   $text1 = filter_var($text1, FILTER_SANITIZE_STRING);
   $text2 = $_POST['text2'];
   $text2 = filter_var($text2, FILTER_SANITIZE_STRING);
   $text3 = $_POST['text3'];
   $text3 = filter_var($text3, FILTER_SANITIZE_STRING); 

   $update_category = $conn->prepare("UPDATE `tieude` SET id = ?, text1 = ?, text2 = ?, text3 = ?, name = ? WHERE id = ?");
   $update_category->execute([$xid, $name,$text1, $text2, $text3, $id]);

   $message[] = 'Tiêu đề được cập nhật!';
   header('location:mid');

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../mid_img/'.$image;

   if(!empty($image)){
      if($image_size > 5000000){
         $message[] = 'kích thước hình ảnh quá lớn!';
      }else{
         $update_image = $conn->prepare("UPDATE `tieude` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../mid_img/'.$old_image);
         $message[] = 'Hình ảnh được cập nhật!';
      }
   }

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

            <section class="update-product">

   <h1 class="heading">Cập nhật tiêu đề</h1>

   <?php
      $update_id = $_GET['update'];
      $show_mid = $conn->prepare("SELECT * FROM `tieude` WHERE id = ?");
      $show_mid->execute([$update_id]);
      if($show_mid->rowCount() > 0){
         while($fetch_mid = $show_mid->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $fetch_mid['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_mid['image']; ?>">
      <span>Thứ tự hiển thị</span>
      <input type="number" required placeholder="ví dụ 1-2-3-4" name="xid" class="box" value="<?= $fetch_mid['id']; ?>">
      <span>update Tiêu đề</span>
      <input type="text" required placeholder="..." name="name" class="box" value="<?= $fetch_mid['name']; ?>">
      <span>Text 1</span>
      <input type="text" required placeholder="..." name="text1" class="box" value="<?= $fetch_mid['text1']; ?>">
      <span>Text 2</span>
      <input type="text" required placeholder="..." name="text2" class="box" value="<?= $fetch_mid['text2']; ?>">
      <span>Text 3</span>
      <input type="text" required placeholder="..." name="text3" class="box" value="<?= $fetch_mid['text3']; ?>">
      <span>Cập nhật hình ảnh</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      
      <div class="flex-btn">
         <input type="submit" value="update" class="btn" name="update">
         <a href="mid" class="option-btn">Trở lại</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">chưa có loại sp nào được thêm vào!</p>';
      }
   ?>

</section>
            
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>