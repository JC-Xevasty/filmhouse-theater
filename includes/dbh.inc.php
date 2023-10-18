<?php

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "filmHouse";

//Connect to database
$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

//Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}