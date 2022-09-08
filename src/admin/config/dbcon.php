<?php
    $serverName = 'localhost';
    $dBUsername = 'root';
    $dBPassword = '';
    $dBName = 'benj_online';

    $conn =mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

    if(!$conn){
        header('Location: ../errors/dberror');
        die();
    }
