<?php

// Account Login
function loginUser($conn, $username, $password)
{
    $sql = "SELECT * FROM accounts WHERE BINARY acctUsername = ? AND BINARY acctPassword = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);

    if ($row) {
        session_start();
        $_SESSION["accountID"] = $row["acctID"];
        $_SESSION["accountUser"] = $row["acctUsername"];
        $_SESSION["accountType"] = $row["accessType"];
        header("location: ../index.php");
        exit();
    } else {
        header("location: ../index.php?error=1");
        exit();
    }
}

// Account Error Handling
function checkExistingAccount($username)
{
    require 'dbh.inc.php';
    $sql = "SELECT * FROM accounts WHERE BINARY acctUsername = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);

    if ($row) {
        echo "true";
        return true;
    } else {
        echo "false";
        return false;
    }
}

function shortPassword($password)
{
    if (strlen($password) < 8) {
        return true;
    } else {
        return false;
    }
}

function weakPassword($password)
{
    $number = preg_match('@[0-9]@', $password);
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);

    if (!$number || !$uppercase || !$lowercase) {
        return true;
    } else {
        return false;
    }
}

function invalidEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}