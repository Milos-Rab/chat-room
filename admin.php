<?php
    session_start();
    include 'mysql.php';
    include 'mongodb.php';

    $page = "index/login";

    if(!empty($_POST)){
        extract($_POST);
        $stmt1=$mysql_db->prepare('SELECT users.name, users.user_id, users.age, users.gender, users.check_timeout, users.`user_role`, users.`ip_address`, users.`chat_room` FROM users WHERE users.`name`= ? AND users.`user_role`="admin"');
        $stmt1->bind_param('s', $username);
        $stmt1->execute();
        $res1 = $stmt1->get_result();
        if($res1->num_rows>0){
            $stmt2 = $mysql_db->prepare("SELECT * FROM password WHERE password.`user_id`=? AND password.`password_hash`=MD5(?)");

            while($user=$res1->fetch_assoc()){
                $stmt2->bind_param('ss', $user['user_id'], $password);
                $stmt2->execute();
                $res2=$stmt2->get_result();
                if($res2->num_rows>0){
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['age'] = $user['age'];
                    $_SESSION['gender'] = $user['gender'];
                    $_SESSION['user_role'] = $user['user_role'];
                    $_SESSION['chat_room'] = $user['chat_room'];
                    $_SESSION['admin_confirm']="confirm";
                    ob_start();
                    header("Location: ./admin_panel.php");
                    ob_flush();
                    die("");
                }
            }
        }
    }

    include './template/header.php';

?>

<div class="container-login100">
    <div class="wrap-login100 p-t-35 p-b-20">
        <form class="login100-form validate-form" method="post" action="./admin.php">
            <h3 class="m-t-20 m-b-40" style="text-align:center;">Welcome to Chat Room</h3>
            <div class="wrap-input100 validate-input m-t-15 m-b-35" data-validate="Enter user Name">
                <input class="input100" type="text" name="username">
                <span class="focus-input100" data-placeholder="User Name"></span>
            </div>
            <div class="wrap-input100 validate-input m-b-50" data-validate="Enter Password">
                <input class="input100" type="password" min="1" max="150" name="password">
                <span class="focus-input100" data-placeholder="Password"></span>
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
