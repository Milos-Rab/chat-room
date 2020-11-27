<?php
    $mongo_host = 'localhost';
    $mongo_port = 27017;

    $manager = new MongoDB\Driver\Manager("mongodb://$mongo_host:$mongo_port/");
    
    var_dump($manager);
?>