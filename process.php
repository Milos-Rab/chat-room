<?php
session_start();

if(empty($_POST) || empty($_SESSION)){
    header("Location: ./index.php");
}
extract($_SESSION);
extract($_POST);

include './mysql.php';
include './mongodb.php';

switch($type){
case 'GET_NEW_MESSAGES':
    
    $filter = ["from"=>$active_roommate, "read"=>"none"];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);
    $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);

    echoMongoDBReadResult($messages);
    updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $active_roommate);
break;
case 'ADD_NEW_MESSAGE':
    $message_document = ["from"=>$user_id, "to"=>$active_roommate, "content"=>$message, "time"=>time(), "read"=>"none"];
    $inserts = new MongoDB\Driver\BulkWrite();

    $inserts->insert($message_document);
    $mongodb->executeBulkWrite("$mongodb_name.$collection_message", $inserts);

    $filter = ["from"=>$active_roommate, "read"=>"none"];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);
    $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);

    echo "[";
    echoMongoDBReadResult($messages);
    echo ",".time()."]";
    updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $active_roommate);
break;
case 'CHECK_OUT':
    $query = "UPDATE users SET `check_timeout`=CURRENT_TIMESTAMP WHERE `user_id`='".$user_id."';";
    $mysql_db->query($query);

break;
case 'GET_ROOM_USERS':
    try{
        $query = "SELECT users.`name`, users.`age`, users.`gender`, users.`user_id`, user_role.`role_name` FROM users, user_role WHERE users.`user_role`=user_role.`id` AND `chat_room`=".$chat_room." AND users.`user_id`<>'".$user_id."';";
        $res = $mysql_db->query($query);
        $rows = array();
        while($r = $res->fetch_assoc()){
            $rows[]=$r;
        }
        echo json_encode($rows);
    }catch(Exception $e){
        echo "error";
    }
break;
case 'GET_ROOMMATE':
    try{
        $query = "SELECT `roommate` FROM roommate WHERE `you`='".$user_id."' AND `room_id`=".$chat_room.";";
        $res = $mysql_db->query($query);
        $rows=array();
        while($r=$res->fetch_assoc()){
            $rows[]=$r;
        }
        echo json_encode($rows);
    }catch(Exception $e){
        echo "error";
    }
break;
case 'ADD_ROOMMATE':
    if($user_id !== $roommate_id){
        $query = "INSERT INTO `roommate`(`you`, `roommate`, `room_id`, `is_new`) VALUES('".$user_id."', '".$roommate_id."', ".$chat_room.", 'no');";
        $query.="INSERT INTO `roommate`(`you`, `roommate`, `room_id`) SELECT '".$roommate_id."', '".$user_id."', ".$chat_room." FROM DUAL WHERE NOT EXISTS (SELECT * FROM roommate WHERE you='".$roommate_id."' AND roommate='".$user_id."');";

        if($mysql_db->multi_query($query)==TRUE){            
            echo "success";
        }
    }
break;
case 'GET_ACTIVE_MESSAGES':
    $filter = ['$or'=>[['from'=>$user_id, 'to'=>$active_roommate],['to'=>$user_id, 'from'=>$active_roommate]]];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);
    $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);
    echoMongoDBReadResult($messages);
    updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $active_roommate);
break;
case 'DISMISS_ROOMMATE':
    $query = "DELETE FROM roommate WHERE you='".$user_id."' AND roommate='".$roommate."';";
    if($mysql_db->query($query)==TRUE){
        echo 'success';
    }
break;
default:
    echo "type error";
}

function echoMongoDBReadResult($messages){
    echo "[";
    $i=0;
    foreach($messages as $message){
        if($i!=0) echo ",";
        echo '{"from":"'.$message->from.'","to":"'.$message->to.'","content":"'.$message->content.'","time":'.$message->time.'}';
        $i++;
    }
    echo "]";
}

function updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $active_roommate){
    $updates = new MongoDB\Driver\BulkWrite();
    $updates->update(
        ['from' => $active_roommate, 'read' => 'none'],
        ['$set' => ['read' => 'yes']],
        ['multi' => true, 'upsert' => false]
    );

    $result = $mongodb->executeBulkWrite("$mongodb_name.$collection_message", $updates);
}

?>
