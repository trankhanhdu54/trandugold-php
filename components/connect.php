<?php




$db_name = 'mysql:host=bcqwadyti6tkx5kmx6vh-mysql.services.clever-cloud.com:3306;dbname=bcqwadyti6tkx5kmx6vh';
$user_name = 'ui9y7a2se7y5uvxq';
$user_password = 'rKE3F8keTXbU7DHzL77Y';
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
$conn = new PDO($db_name, $user_name, $user_password);


if (!$conn){
    die("Error". mysqli_connect_error());
}

?>
