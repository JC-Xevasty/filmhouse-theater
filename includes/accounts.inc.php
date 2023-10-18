<?php

include 'functions.inc.php';

if (isset($_POST["save"])) {
    updateAccountDetails();
} else if (isset($_POST["create"])) {
    createAccount();
} else if (isset($_POST["delete-account"])) {
    deleteAccount();
}

//Retrieve Account Details by ID
function getAccountDetails($acctID)
{
    require_once 'dbh.inc.php';

    $sql = "SELECT * FROM accounts WHERE acctID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $acctID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultData);

    if ($row) {
        $acctFName = $row["acctFName"];
        $acctLName = $row["acctLName"];
        $acctUserame = $row["acctUsername"];
        $acctPassword = $row["acctPassword"];
        $acctEmail = $row["acctEmail"];
        $acctContact = $row["acctContact"];
        $accessType = $row["accessType"];
    }

    $accountDetails = array($acctFName, $acctLName, $acctUserame, $acctPassword, $acctEmail, $acctContact, $accessType);

    return $accountDetails;
}

// Retrieve all Accounts from database
function retrieveAccounts()
{
    require_once 'dbh.inc.php';

    $sql = "SELECT * FROM accounts";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Update Account
function updateAccountDetails()
{
    session_start();
    require_once 'dbh.inc.php';
    $set = "acctFName=?, acctLName=?, acctUsername=?, acctPassword=?, acctEmail=?, acctContact=?";
    $sql = "UPDATE accounts SET " . $set . " WHERE acctID = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    $updFName = $_POST["firstName"];
    $updLName = $_POST["lastName"];
    $updUser = $_POST["userName"];
    $updPass = $_POST["password"];
    $updEmail = $_POST["email"];
    $updContact = $_POST["contact"];

    // Account Details Error Handling
    if ($_SESSION['accountUser'] != $updUser) {
        if (checkExistingAccount($updUser)) {
            header("location: ../dashboard.php?error=1");
            exit();
        }
    } else if (shortPassword($updPass)) {
        header("location: ../dashboard.php?error=2");
        exit();
    } else if (weakPassword($updPass)) {
        header("location: ../dashboard.php?error=3");
        exit();
    } else if (invalidEmail($updEmail)) {
        header("location: ../dashboard.php?error=4");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssss", $updFName, $updLName, $updUser, $updPass, $updEmail, $updContact, $_SESSION["accountID"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../dashboard.php");
    exit();
}

// Add Account to Database
function createAccount()
{
    require_once 'dbh.inc.php';

    $sql = "INSERT INTO accounts (acctFName, acctLName, acctUsername, acctPassword, acctEmail, acctContact, accessType) VALUES (?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    $newFName = $_POST["firstName"];
    $newLName = $_POST["lastName"];
    $newUser = $_POST["userName"];
    $newPass = $_POST["password"];
    $newEmail = $_POST["email"];
    $newContact = $_POST["contact"];
    $newType = $_POST["rdoType"];

    // Account Details Error Handling
    if (checkExistingAccount($newUser)) {
        header("location: ../create-account.php?error=1");
        exit();
    } else if (shortPassword($newPass)) {
        header("location: ../create-account.php?error=2");
        exit();
    } else if (weakPassword($newPass)) {
        header("location: ../create-account.php?error=3");
        exit();
    } else if (invalidEmail($newEmail)) {
        header("location: ../create-account.php?error=4");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssss", $newFName, $newLName, $newUser, $newPass, $newEmail, $newContact, $newType);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../dashboard-accounts.php");
    exit();
}

// Remove Account from database
function deleteAccount()
{
    require_once 'dbh.inc.php';

    $rowCount = count($_POST["acctID"]);
    $sql = "DELETE FROM accounts WHERE acctID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    for ($i = 0; $i < $rowCount; $i++) {
        mysqli_stmt_bind_param($stmt, "i", $_POST["acctID"][$i]);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    header("location: ../dashboard-accounts.php");
    exit();
}