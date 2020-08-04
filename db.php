<?php
    $serverName = 'localhost';
    $username = 'root';
    $password = 'mysql';
    $db_name = 'categories';

    $conn = new mysqli($serverName, $username, $password, $db_name);
    mysqli_set_charset($conn, "utf8");
