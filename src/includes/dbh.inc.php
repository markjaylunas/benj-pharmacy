<?php
    // $serverName = 'localhost';
    // $dBUsername = 'root';
    // $dBPassword = '';
    // $dBName = 'benj_db';

    $serverName = 'localhost';
    $dBUsername = 'u322780409_markjaylunas';
    $dBPassword = "can'tConnectHostinger01";
    $dBName = 'u322780409_benj_db';

    $conn =mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

    if(!$conn){
        die("Connection failed" . mysqli_connect_error());
    }
