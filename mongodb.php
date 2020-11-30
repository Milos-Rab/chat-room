<?php
    $mongo_host = 'localhost';
    $mongo_port = 27017;

    $mongodb = new MongoDB\Driver\Manager("mongodb://$mongo_host:$mongo_port/");

    $mongodb_name="chat_room";
    $collection_message = "messages";

?>