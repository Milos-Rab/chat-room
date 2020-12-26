<?php
    session_start();

    if(empty($_SESSION)){
        ob_start();
        header("Location: ./index.php");
        ob_flush();
        die();
    }
    include './mysql.php';
    global $id;

    extract($_GET);

    if(!$id){
        ob_start();
        header("Location: ./chatroom.php?id=".$_SESSION['chat_room']);
        ob_flush();
        die();        
    }else{
        $room_stmt = $mysql_db->prepare("SELECT count(*) cnt FROM rooms WHERE id=?");
        $room_stmt->bind_param('i', $id);
        $room_stmt->execute();
        $room_cnt=$room_stmt->get_result();
        $cnt = $room_cnt->fetch_assoc();
        if($cnt['cnt']==0){
?>
    <h1>There is no room(id=<?php echo htmlspecialchars($id);?>)</h1>
    <p>will you go to other room <a href="./chatroom.php?id=<?php echo "".$_SESSION['chat_room'] ?>"><?php echo "".$_SESSION['chat_room'] ?></a></p>
<?php
            die();
        }else{
            $chat_room_stmt = $mysql_db->prepare("SELECT `chat_room` FROM users WHERE `user_id`=?");
            $chat_room_stmt->bind_param('s', $_SESSION['user_id']);
            $chat_room_stmt->execute();
            $chat_room_res = $chat_room_stmt->get_result();
            $user_chat_room = $chat_room_res->fetch_assoc();

            if($id!=$user_chat_room['chat_room']){
                $chat_room_update = $mysql_db->prepare("UPDATE users SET `chat_room`=? WHERE `user_id`=?");
                $chat_room_update->bind_param('is', $id, $_SESSION['user_id']);
                $chat_room_update->execute();
                $_SESSION['chat_room']=$id;
                ob_start();
                header("Location: ./chatroom.php?id=".$id);
                ob_flush();
                die();    
            }
        }
    }

    $time=time();
    $stmt = $mysql_db->prepare('UPDATE users SET `check_timeout`= ? WHERE `user_id`=?');
    $stmt->bind_param('is', $time, $_SESSION["user_id"]);
    $stmt->execute();

    $page = "chatroom";  
    include './template/header.php';

    //var_dump($_SESSION);
?>

<div class="chat-container">
    <div class="tabs">
        <div class="toggle-show" id="roommate-list-toggle"></div>
        <div class="user-search"></div>
        <div class="roommate-list">
            <div class="loader-container"><div class="loader"></div></div>
            <!-- <div class="roommate-list-item admin">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
            <div class="roommate-list-item logged-out">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
            <div class="roommate-list-item logged-in active">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message-content">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <div class="chat-pan">
        <div class="chat">
            <div class="loader-container hidden"><div class="loader"></div></div>
            <div class="chat-list">
                <!-- <div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div> -->
            </div>
        </div>
        <div class="send">
            <form class="message-sender">
                <div class="input-group mt-3">
                    <input type="text" class="form-control" placeholder="Type your message..." style="z-index:0">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit" style="z-index:1">Send</button>  
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="users">
        <div class="toggle-show" id="roommate-list-toggle"></div>
        <ul class="list-group list-group-flush room-user-list">
            <div class="loader-container "><div class="loader"></div></div>
        </ul>
        <div class="who-with-chat">
            <div class="loader-container hidden"><div class="loader"></div></div>            
        </div>
    </div>
</div>
<audio src="./assets/sound/notification.mp3" id="notification_sound"></audio>

<?php
    include './template/footer.php';
?>