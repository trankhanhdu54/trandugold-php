<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:dangnhap');
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
            <div class="cardBox">


                <div class="card">
                    <div>
                    <?php
                        $select_noidung = $conn->prepare("SELECT * FROM `noidung`");
                        $select_noidung->execute();
                        $numbers_of_noidung = $select_noidung->rowCount();
                    ?>
                        <div class="numbers"><?= $numbers_of_noidung; ?></div>
                        <div class="cardName">Nội dung</div>
                    </div>

                    <div class="iconBx">
                    <ion-icon name="newspaper-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                    <?php
                        $select_congviec = $conn->prepare("SELECT * FROM `congviec`");
                        $select_congviec->execute();
                        $numbers_of_congviec = $select_congviec->rowCount();
                    ?>
                        <div class="numbers"><?= $numbers_of_congviec; ?></div>
                        <div class="cardName">Công việc</div>
                    </div>

                    <div class="iconBx">
                    <ion-icon name="code-slash-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                    <?php
                        $select_messages = $conn->prepare("SELECT * FROM `messages`");
                        $select_messages->execute();
                        $numbers_of_messages = $select_messages->rowCount();
                    ?>
                        <div class="numbers"><?= $numbers_of_messages; ?></div>
                        <div class="cardName">Tin nhắn</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>

                
            </div>

            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Tiêu Đề New</h2>
                        <a href="dongthoigian" class="btn">View All</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Tiêu đề</td>
                                <td>Năm</td>
                                <td>Text</td>
                                <td>Địa điểm</td>
                                
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            $select_orders = $conn->prepare("SELECT * FROM `noidung` ORDER BY id DESC LIMIT 6;");
                            $select_orders->execute();
                            if($select_orders->rowCount() > 0){
                                while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                        ?>
                            <tr>
                                <td><?= $fetch_orders['name']; ?></td>
                                <td><?= $fetch_orders['nam']; ?></td>
                                <td><?= $fetch_orders['text']; ?></td>
                                <td><?= $fetch_orders['namelink']; ?></td>
                            
                            </tr>
                            <?php
                                }
                            }else{
                                echo '<p class="empty">chưa có nội dung nào!</p>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- ================= New Customers ================ -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Lời nhắn của ai đó</h2>
                    </div>

                    <table>
                    <?php
                            $select_account = $conn->prepare("SELECT * FROM `messages` ORDER BY id DESC LIMIT 4");
                            $select_account->execute();
                            if($select_account->rowCount() > 0){
                                while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
                        ?>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="assets/imgs/customer02.jpg" alt=""></div>
                            </td>
                            <td>
                                <h5><?= $fetch_accounts['name']; ?> </h5>
                                <br>
                                <h5><?= $fetch_accounts['email']; ?> </h5>
                                <br>
                                <h5>Lời nhắn: <?= $fetch_accounts['message']; ?> </h5>
                            </td>
                        </tr>
                        <?php
                                }
                            }else{
                                echo '<p class="empty">Chưa có ai đăng ký!</p>';
                            }
                            ?>

                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>