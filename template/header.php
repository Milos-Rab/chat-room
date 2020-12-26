<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Chat Room</title>
        <meta name="keyword" content="chat room">
        <meta name="description" content="chat room">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/css/toastr.min.css"> 
        <link rel="stylesheet" type="text/css" href="./assets/css/util.css">
        <link rel="stylesheet" type="text/css" href="./assets/css/main.css">
<?php
    if($page == "index/admin_panel"){
?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<?php
    }
?>
        <link rel="stylesheet" href="./assets/css/chatroom.css">     
    </head>
    <body>
        <div class="chat-pan">
            <div class="chat-header d-flex justify-content-between" style="background-color:#0070f3;">
            <?php if(!empty($_SESSION)){ ?>        
                <div class="user-profile">
                    <span class="name" style="font-weight:500;"><?php echo $_SESSION['name']?></span> <span class="age">(<?php echo $_SESSION['age']?>)</span> <span class="gender" style="font-size:70%"><?php echo $_SESSION['gender']?></span>
                    <span class="user_id" style="display:none;"><?php echo $_SESSION['user_id']?></span>
                </div>
                <?php }?>
            <?php if(!empty($_SESSION)){?>
                <button class="btn btn-primary" id="logout">Log Out</button>
            <?php }?>
            </div>