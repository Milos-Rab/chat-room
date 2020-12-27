<?php
session_start();
    //die(json_encode($_SESSION));

    if(empty($_SESSION)) {
        ob_start();
        header("Location: ./admin.php");
        ob_flush();
    }
    if($_SESSION['user_role']!='admin'){
        ob_start();
        header("Location: ./admin.php");
        ob_flush();
    }

    extract($_POST);

    include "mysql.php";
    include "mongodb.php";

    $users_res = $mysql_db->query("SELECT name, user_id, age, gender, room_name FROM users, rooms WHERE users.`chat_room`=rooms.`id`;");
    $users=[];
    while($row=$users_res->fetch_assoc()){
        $users[]=$row;
    }

    switch($type){
    case "SCAN_MESSAGE":
        $filter = [];
        $option = ['$sort'=>['time'=>1]];
        $query = new MongoDB\Driver\Query($filter, $option);
        $result = $db->executeQuery("$mongodb_name.$collection_message", $query);
        break;
    case "ADD_ROOM":
        
        $stmt2 = $mysql_db->prepare("SELECT COUNT(*) AS cnt FROM rooms WHERE room_name=?");
        $stmt2->bind_param('s', $room_name);
        $stmt2->execute();
        $res=$stmt2->get_result();
        $cnt=$res->fetch_assoc();

        echo "{";
        if($cnt['cnt']==0){
            $stmt = $mysql_db->prepare("INSERT INTO rooms(`room_name`) VALUES(?)");
            $stmt->bind_param('s', $room_name);
            
            if($stmt->execute()){
                echo '"result":"success","data":{"insert_id":'.$stmt->insert_id.'}';
            }else{
                echo '"result":"error","data":{"message":"'.$stmt->error.'"}';
            }
        }else{
            echo '"result":"error","data":{"message":"Duplicated the room name, Please try using another Room name"}';
        }
        echo "}";
        break;
    case "EDIT_ROOM":
        $stmt = $mysql_db->prepare("UPDATE rooms SET room_name=? WHERE id=?");
        $stmt->bind_param('si', $new_name, $room_id);
        
        echo "{";
        if($stmt->execute()){
            echo '"result":"success","data":{"insert_id":'.$stmt->insert_id.'}';
        }else{
            echo '"result":"error","data":{"message":"'.$stmt->error.'"}';
        }
        echo "}";
        break;
    case "DELETE_ROOM":
        $res=$mysql_db->query("SELECT COUNT(*) AS cnt FROM rooms;");
        $cnt=$res->fetch_assoc();

        echo "{";
        if($cnt['cnt']<=1){
            echo '"result":"error","data":{"message":"Could not delete a room. You must have at least one chatting room."}';
        }else{
            $stmt = $mysql_db->prepare("DELETE FROM rooms WHERE id=?;");
            $stmt->bind_param('i', $room_id);
            if($stmt->execute()){
                echo '"result":"success","data":{"insert_id":'.$stmt->insert_id.'}';
            }else{
                echo '"result":"error","data":{"message":"'.$stmt->error.'"}';
            }
        }
        echo "}";

        break;
    case "IP_OPERATE":
        if($action=="opened"){
            $stmt1 = $mysql_db->prepare("SELECT COUNT(*) AS cnt FROM blocked_users WHERE ip_address=?");
            $stmt1->bind_param('s', $ip_address);
            $stmt1->execute();
            $row1 = $stmt1->get_result();
            $cnt = $row1->fetch_assoc();
            if($cnt['cnt']>=1){
                $stmt2=$mysql_db->prepare("DELETE FROM blocked_users WHERE ip_address=?");
                $stmt2->bind_param('s', $ip_address);
                
                if($stmt2->execute()){
                    echo '{"result":"success", "message":"success"}';
                }else{
                    echo '{"result":"error", "message":"Something went wrong, please try again later."}';
                }
            }
        }else if($action=="blocked"){
            $stmt1 = $mysql_db->prepare("SELECT COUNT(*) AS cnt FROM users WHERE ip_address=?");
            $stmt1->bind_param('s', $ip_address);
            $stmt1->execute();
            $row1 = $stmt1->get_result();
            $cnt = $row1->fetch_assoc();
            if($cnt['cnt']>=1){
                $stmt2=$mysql_db->prepare("INSERT INTO blocked_users(`ip_address`, `note`, `blocked`) VALUES(?,'block','blocked')");
                $stmt2->bind_param('s', $ip_address);
                if($stmt2->execute()){
                    echo "success";
                }else{
                    echo "error";
                }
            }

        }

        break;
    case "GET_MESSAGES":
        $users_res = $mysql_db->query("SELECT users.`name`, users.age, users.`gender`, users.`user_id` FROM users ORDER BY users.`check_timeout` DESC;");
        $users = [];
        while ($row=$users_res->fetch_assoc()){
            $users[]=$row;
        }
        echo '{"users":'.json_encode($users).",";

        $filter=[];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        $messages = $mongodb->executeQuery("$mongodb_name.$collection_message", $read);
        echo '"messages":[';
        $i=0;
        foreach($messages as $message){
            if($i!=0) echo ",";
            echo '{"id":"'.$message->_id.'","from":"'.$message->from.'","to":"'.$message->to.'","content":"'.$message->content.'","time":'.$message->time.'}';
            $i++;
        }
        echo "]}";

        break;
    case "DELETE_MESSAGE":

        $bulk = new MongoDB\Driver\BulkWrite;       
        foreach($checked_rows as $checked_row){
            $bulk->delete(['_id' => new MongoDB\BSON\ObjectID($checked_row)], ['limit' => 1]);
        }
        $result = $mongodb->executeBulkWrite("$mongodb_name.$collection_message", $bulk);

        echo "success";
        break;
    default:
        echo "Type error.";
    }

?>