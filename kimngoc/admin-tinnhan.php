<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:tinnhan');
}

?>


<!DOCTYPE html>
<html lang="vn">

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
<section class="messages">

   <h1 class="heading">Tin nhắn</h1>

   <div class="box-container">

   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages` ORDER BY id DESC");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Tên: <span><?= $fetch_messages['name']; ?></span> </p>
      <p> email: <span><?= $fetch_messages['email']; ?></span> </p>
      <p> Lời nhắn: <span><?= $fetch_messages['message']; ?></span> </p>
      <a href="tinnhan?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('xóa tin nhắn này?');">Xóa</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Chưa có tin nhắn nào</p>';
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