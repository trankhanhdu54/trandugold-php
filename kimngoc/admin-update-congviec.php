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
   $text = $_POST['text'];
   $text = filter_var($text, FILTER_SANITIZE_STRING);

   $update_congviec = $conn->prepare("UPDATE `congviec` SET id = ?, name = ?, text = ? WHERE id = ?");
   $update_congviec->execute([$xid, $name,$text, $id]);

   $message[] = 'Công việc được cập nhật!';
   header("location:congviec");

  
   
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

   <h1 class="heading">Cập nhật Danh Mục</h1>

   <?php
      $update_id = $_GET['update'];
      $show_congviec = $conn->prepare("SELECT * FROM `congviec` WHERE id = ?");
      $show_congviec->execute([$update_id]);
      if($show_congviec->rowCount() > 0){
         while($fetch_congviec = $show_congviec->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $fetch_congviec['id']; ?>">
      
      <span>Thứ tự hiển thị</span>
      <input type="number" required placeholder="ví dụ 1-2-3-4" name="xid" maxlength="100" class="box" value="<?= $fetch_congviec['id']; ?>">
      <span>update name</span>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box" value="<?= $fetch_congviec['name']; ?>">
      <span>update nội dung</span>
      <input type="text" required placeholder="enter product name" name="text" class="box" value="<?= $fetch_congviec['text']; ?>">
   
      
      <div class="flex-btn">
         <input type="submit" value="update" class="btn" name="update">
         <a href="congviec" class="option-btn">Trở lại</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">chưa có công việc nào được thêm vào!</p>';
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