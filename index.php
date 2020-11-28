<?php
    session_start();
    include 'mysql.php';
    include './template/header.php';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $query = "SELECT COUNT(*) AS `cnt` FROM `users` WHERE `ip_address`='".$ip."';";
    $rs = $mysql_db->query($query);
    $result = $rs->fetch_assoc();

    if($result['cnt']==="1"){
        $query1 = "SELECT users.id, users.name, users.user_id, users.gender, users.check_timeout, user_role.`role_name`, users.`ip_address` FROM users, user_role WHERE users.`user_role`=user_role.`id` AND users.`ip_address`= '".$ip."';";
        //die($query1);
        $rs1 = $mysql_db->query($query1);
        $user = $rs1->fetch_assoc();
        
        header("Location: ./chatroom.php");
    }
    
    $page = "index/login";


    if(!empty($_POST)){
        extract($_POST);
    }else{

    }

?>

<div class="container-login100">
    <div class="wrap-login100 p-t-35 p-b-20">
        <form class="login100-form validate-form" type="post" <?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>>
            <h2 class="m-t-20 m-b-20" style="text-align:center;">Log in</h2>
            <div class="wrap-input100 validate-input m-t-15 m-b-35" data-validate="Enter username">
                <input class="input100" type="text" name="username">
                <span class="focus-input100" data-placeholder="User Name"></span>
            </div>
            <div class="wrap-input100 validate-input m-b-50" data-validate="Enter Age">
                <input class="input100" type="number" min="1" max="150" name="pass">
                <span class="focus-input100" data-placeholder="Age"></span>
            </div>
            <div class="wrap-input100 m-b-50" style="border:none;">
                <label class="radio-inline"><input type="radio" name="optradio" value="male" checked>Male</label>
                <label class="radio-inline"><input type="radio" name="optradio" value="female">Female</label>
            </div>
            <div class="container-login100-form-btn">
            <button class="login100-form-btn">Login</button>
            </div>
        </form>
    </div>
</div>

<?php
    include './template/footer.php';
?>
