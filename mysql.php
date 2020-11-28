<?php
    $server_name = "localhost";
    $user_name = "root";
    $user_password = "";
    $db_name = "chat_room";

    // create mysql connection;
    $mysql_db = new mysqli($server_name, $user_name, $user_password, $db_name);
    // Check connection
    if ($mysql_db->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $mysql_db->set_charset('utf8');
?>