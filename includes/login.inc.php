<?php

// Login Account
if (isset($_POST["login"])){
    $username = $_POST["loginUser"];
    $password = $_POST["loginPass"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    loginUser($conn, $username, $password);
} else {
    header("location: ../index.php");
    exit();
}