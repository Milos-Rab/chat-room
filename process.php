<?php
session_start();

if(empty($_POST) || empty($_SESSION)){
    //header("Location: ./index.php");
}
extract($_SESSION);
extract($_POST);

include './mysql.php';
include './mongodb.php';

switch($type){
case 'GET_UPDATE_DATA':
    $time = time();
    $update_stmt = $mysql_db->prepare("UPDATE users SET check_timeout=? WHERE user_id=?");
    $update_stmt->bind_param('is',$time, $user_id);
    $update_stmt->execute();
    $update_stmt->close();
    
    $time = time();
    $user_stmt = $mysql_db->prepare("SELECT *,?-created_date AS crt,?-check_timeout as cht FROM users WHERE user_id<>? AND chat_room=?");
    $user_stmt->bind_param('iisi',$time, $time, $user_id, $chat_room);
    $user_stmt->execute();
    $res_users = $user_stmt->get_result();
    $user_stmt->close();

    $users = [];
    while($user_row=$res_users->fetch_assoc()){
        $users[]=$user_row;
    }
    echo "[";
    echo json_encode($users).",";

    $time = time();
    $roommate_stmt = $mysql_db->prepare("SELECT roommate, ?-created_time as crt FROM roommate WHERE you=?");
    $roommate_stmt->bind_param('is', $time, $user_id);
    $roommate_stmt->execute();
    $res_roommate = $roommate_stmt->get_result();
    $roommate_stmt->close();
    $roommates = [];
    while($roommate_row=$res_roommate->fetch_assoc()){
        $roommates[]=$roommate_row;
    }
    echo json_encode($roommates).",";
    
    $filter = ['$and'=>[["to"=>$user_id], ["read"=>"none"]]];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);
    $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);
    echoMongoDBReadResult($messages);
    echo "]";
    updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $user_id, $active_roommate);
break;
case 'GET_USER_DATA':
    $stmt = $mysql_db->prepare('SELECT * FROM users WHERE user_id<>? AND chat_room= ?');
    $stmt->bind_param('si', $user_id, $chat_room);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $users = [];
    while($row=$res->fetch_assoc()){
        $users[]=$row;
    }
    
    echo "[",json_encode($users).",";

    $stmt2=$mysql_db->prepare('SELECT roommate FROM roommate WHERE you=?');
    $stmt2->bind_param('s',$user_id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $stmt2->close();
    $roommates=[];
    while($row=$res2->fetch_assoc()){
        $roommates[]=$row;
    }
    echo json_encode($roommates).",".time()."]";

break;
case 'ADD_NEW_MESSAGE':
    if($message){
        $message_document = ["from"=>$user_id, "to"=>$active_roommate, "content"=>"$message", "time"=>time(), "read"=>"none"];
        $inserts = new MongoDB\Driver\BulkWrite();

        $inserts->insert($message_document);
        $mongodb->executeBulkWrite("$mongodb_name.$collection_message", $inserts);

        $filter = ['$and'=>[["from"=>$active_roommate], ["to"=>$user_id], ["read"=>"none"]]];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);

        echo "[";
        echoMongoDBReadResult($messages);
        echo ",".time()."]";
        updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $user_id, $active_roommate);
    }
break;
case 'ADD_ROOMMATE':
    if($user_id !== $roommate_id){
        $time = time();
        $stmt1 = $mysql_db->prepare("INSERT INTO `roommate`(`you`, `roommate`, `room_id`, `created_time`) VALUES(?,?,?,?)");
        $stmt1->bind_param('ssii', $user_id, $roommate_id, $chat_room, $time);
        $stmt1->execute();
        $stmt1->close();
        $stmt2 = $mysql_db->prepare("INSERT INTO `roommate`(`you`, `roommate`, `room_id`, `created_time`) SELECT ?,?,?,? FROM DUAL WHERE NOT EXISTS (SELECT * FROM roommate WHERE you=? AND roommate=?)");
        $stmt2->bind_param('ssiiss', $roommate_id, $user_id, $chat_room, $time,$roommate_id,$user_id);
        $stmt2->execute();
        $stmt2->close();
        echo "success";
    }
break;
case 'GET_ACTIVE_MESSAGES':
    $filter = ['$or'=>[['from'=>$user_id, 'to'=>$active_roommate],['to'=>$user_id, 'from'=>$active_roommate]]];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);
    $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);
    echoMongoDBReadResult($messages);
    updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $user_id, $active_roommate);
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

function updateUnreadMessages($mongodb, $mongodb_name, $collection_message, $user_id, $active_roommate=""){
    $updates = new MongoDB\Driver\BulkWrite();
    
    $updates->update(
        ['$and'=>[['from' => $active_roommate], ['to'=>$user_id], ['read' => 'none']]],
        ['$set' => ['read' => 'yes']],
        ['multi' => true, 'upsert' => false]
    );
    $result = $mongodb->executeBulkWrite("$mongodb_name.$collection_message", $updates);
}

?>
