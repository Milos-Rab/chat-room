<?php
    session_start();
    include 'mysql.php';
    include 'mongodb.php';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
 
    $page = "index/login";

    if(!empty($_POST)){
        
        extract($_POST);
        $stmt1=$mysql_db->prepare('SELECT users.name, users.user_id, users.age, users.gender, users.check_timeout, users.`user_role`, users.`ip_address`, users.`chat_room` FROM users WHERE users.`name`= ? AND users.`age`=? AND users.`gender`=?');
        $stmt1->bind_param('sis', $username, $age, $gender);
        $stmt1->execute();
        $rs1 = $stmt1->get_result();
        if($rs1->num_rows>0){
            $user = $rs1->fetch_assoc();
            $stmt1->close();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['age'] = $user['age'];
            $_SESSION['gender'] = $user['gender'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['chat_room'] = $user['chat_room'];
            ob_start();
            header("Location: ./chatroom.php");
            ob_flush();
            die();
        }else{
            $id=$username.$age;
            $current_time = time();
            $insert_prepare = $mysql_db->prepare('INSERT INTO `users`(`name`, `user_id`, `age`, `gender`, `ip_address`, `created_date`, `check_timeout`) VALUES(?, MD5(?), ?, ?, ?, ?, ?)');
            
            $insert_prepare -> bind_param('ssissii', $username, $id, $age, $gender, $ip, $current_time, $current_time);
            
            if($insert_prepare->execute()){

                $last_id = $mysql_db->insert_id;
                $select_query = "SELECT users.id, users.name, users.user_id,users.age, users.gender, users.check_timeout, users.`user_role`, users.`ip_address`, users.`chat_room` FROM users WHERE users.`id`=".$last_id.";";

                //die($query1);
                if($rs2 = $mysql_db->query($select_query)){
                    $user2 = $rs2->fetch_assoc();
                    $_SESSION['user_id'] = $user2['user_id'];
                    $_SESSION['name'] = $user2['name'];
                    $_SESSION['age'] = $user2['age'];
                    $_SESSION['gender'] = $user2['gender'];
                    $_SESSION['user_role'] = $user2['user_role'];
                    $_SESSION['chat_room'] = $user2['chat_room'];
                }
                //var_dump($_SESSION); die("");
                ob_start();
                header("Location: ./chatroom.php");
                ob_flush();
                die();
            }
        }
    }
    
    include './template/header.php';
?>

<div class="container-login100">
    <div class="wrap-login100 p-t-35 p-b-20">
        <form class="login100-form validate-form" method="post" action="./index.php">
            <h3 class="m-t-20 m-b-40" style="text-align:center;">Welcome to Chat Room</h3>
            <div class="wrap-input100 validate-input m-t-15 m-b-35" data-validate="Enter username">
                <input class="input100" type="text" name="username">
                <span class="focus-input100" data-placeholder="User Name"></span>
            </div>
            <div class="wrap-input100 validate-input m-b-50" data-validate="Enter Age">
                <input class="input100" type="number" min="1" max="150" name="age">
                <span class="focus-input100" data-placeholder="Age"></span>
            </div>
            <div class="wrap-input100 m-b-50" style="border:none;">
                <label class="radio-inline"><input type="radio" name="gender" value="Male" checked>Male</label>
                <label class="radio-inline"><input type="radio" name="gender" value="Female">Female</label>
            </div>
            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>

<?php
    include './template/footer.php';
?>
