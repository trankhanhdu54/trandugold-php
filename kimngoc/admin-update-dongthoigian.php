<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $xid = $_POST['xid'];
   $xid = filter_var($xid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $nam = $_POST['nam'];
   $nam = filter_var($nam, FILTER_SANITIZE_STRING);
   $text = $_POST['text'];
   $text = filter_var($text, FILTER_SANITIZE_STRING);
   $namelink = $_POST['namelink'];
   $namelink = filter_var($namelink, FILTER_SANITIZE_STRING);
   $link = $_POST['link'];
   $link = filter_var($link, FILTER_SANITIZE_STRING);
   

   $update_product = $conn->prepare("UPDATE `noidung` SET id = ?, name = ?, nam = ?, text = ?, namelink = ?, link = ? WHERE id = ?");
   $update_product->execute([$xid, $name, $nam, $text, $namelink, $link, $pid]);

   $message[] = 'Đã được cập nhật!';
   header("location:dongthoigian");

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'kích thước hình ảnh quá lớn!';
      }else{
         $update_image = $conn->prepare("UPDATE `noidung` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'hình ảnh được cập nhật!';
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

   <h1 class="heading">Cập nhật sản phẩm</h1>

   <?php
      $update_id = $_GET['update'];
      $show_noidung = $conn->prepare("SELECT * FROM `noidung` WHERE id = ?;");
      $show_noidung->execute([$update_id]);
      if($show_noidung->rowCount() > 0){
         while($fetch_noidung = $show_noidung->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
   <div style="margin-bottom:10px;" class="flex-btn">
         <input type="submit" value="update" class="btn" name="update">
         <a href="dongthoigian" class="option-btn">Trở lại</a>
      </div>
      <span>Thứ tự hiển thị</span>
      <input type="number" required placeholder="ví dụ 1-2-3-4" name="xid" maxlength="100" class="box" value="<?= $fetch_noidung['id']; ?>">
      <input type="hidden" name="pid" value="<?= $fetch_noidung['id']; ?>">

      <span>update name</span>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box" value="<?= $fetch_noidung['name']; ?>">

      <span>update Năm</span>
      <input type="text" required placeholder="<?= $fetch_noidung['nam']; ?>" name="nam"  class="box" value="<?= $fetch_noidung['nam']; ?>">

      <span>update nội dung</span>
      <input type="text" required placeholder="<?= $fetch_noidung['text']; ?>" name="text"  class="box" value="<?= $fetch_noidung['text']; ?>">

      <span>update Name Link</span>
      <input type="text" required placeholder="<?= $fetch_noidung['namelink']; ?>" name="namelink"  class="box" value="<?= $fetch_noidung['namelink']; ?>">

      <span>update Link</span>
      <input type="text" required placeholder="<?= $fetch_noidung['link']; ?>" name="link"  class="box" value="<?= $fetch_noidung['link']; ?>">

            <?php
            }
         }else{
            echo '<p class="empty">Chưa được thêm vào!</p>';
         }
      ?>
         

            
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>