<?php
    session_start();
    include 'mysql.php';
    include 'mongodb.php';

    $page = "index/admin_panel";

    if(empty($_SESSION)){        
        ob_start();
        header("Location: ./admin.php");
        ob_flush();
        die("");
    }
    
    extract($_SESSION);
    if($user_role!="admin"){
        die("forbidden");
    }

    if($admin_confirm!="confirm"){
        ob_start();
        header("Location: ./admin.php");
        ob_flush();
        die("");
    }

    include './template/header.php';
?>

<?php
    include './template/footer.php';
?>
