<?php
session_start();

// Redirects back to home when there is no account logged in
if (!(isset($_SESSION["accountID"]))) {
    header("location: ../FilmHouse");
    exit();
} else {
    // Redirects back to dashboard when acccessed with a user account
    if (!($_SESSION["accountType"] === "admin")) {
        header("location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="icon" type="image/png" href="img/logo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/all.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php require_once 'header.php' ?>
    <br>
    <section class="mb-5">
        <div class="container">
            <h2 class="text-light cinema-label my-5">Dashboard</h2>
            <div class="row g-2">
                <div class="col-md-3 pe-5 rounded-3">
                    <div class="card dashboard-nav">
                        <div class="list-group list-group-flush p-2">
                            <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnProfile">
                                Manage Profile</button>
                            <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnMovies">
                                Manage Movies</button>
                            <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnSchedules">
                                Manage Schedules</button>
                            <?php if ($_SESSION['accountType'] == 'admin') : ?>
                                <button type="button" class="list-group-item list-group-item-action py-3 active button-nav" id="btnAccounts">Manage Accounts</button>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnTransactions">Manage Transactions</button>
                            <?php else : ?>
                                <button type="button" class="list-group-item list-group-item-action py-3 active button-nav disabled" id="btnAccounts">Manage Accounts</button>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav disabled" id="btnTransactions">Manage Transactions</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm px-4 text-light">
                    <div class="dashboard">
                        <form action="includes/accounts.inc.php" method="POST">
                            <div class="d-flex align-items-center page-title">
                                <span class="dashboard-icon"><i class="bi bi-pencil-square"></i></span>
                                <span>Create Account</span>
                            </div>
                            <br>
                            <div class="d-flex align-items-center justify-content-between mb-2 profile-header">
                                <div class="d-flex">
                                    <span class="">Account Type:</span>
                                    <div class="btn-group mx-3" role="group">
                                        <input type="radio" class="btn-check" name="rdoType" id="rdoAdmin" value="admin" checked>
                                        <label class="btn btnType px-3 shadow-none  btn-types" for="rdoAdmin">Admin Account</label>

                                        <input type="radio" class="btn-check" name="rdoType" id="rdoUser" value="user">
                                        <label class="btn btnType px-3 shadow-none" for="rdoUser">User Account</label>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btnCancel-movie rounded-3 mx-2" id="btnCancel" name="cancel" onclick="cancelAddAccount()">Cancel</button>
                                    <button type="submit" class="btn btnAdd-movie rounded-3" id="btnCreate" name="create">Create Account</button>
                                </div>
                            </div>
                            <div class="personal mt-3 mb-5">
                                <div class="d-flex justify-content-between">
                                    <span class="fs-4 red-font">Personal Information</span>
                                    <?php if (isset($_GET['error'])) : ?>
                                        <span class="fs-5 red-font error-display" id="acctError">
                                            <?php if ($_GET['error'] == 1) : ?>
                                                *Username already exists.
                                            <?php elseif ($_GET['error'] == 2) : ?>
                                                *Password must be at least 8 characters.
                                            <?php elseif ($_GET['error'] == 3) : ?>
                                                *Password must contain numbers, lowercase and uppercase letters.
                                            <?php elseif ($_GET['error'] == 4) : ?>
                                                *Enter valid email.
                                            <?php endif; ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="fs-5 red-font error-display d-none" id="acctError"></span>
                                    <?php endif; ?>
                                </div>
                                <hr>
                                <div class="mx-4">
                                    <div class="row g-5 mb-4">
                                        <div class="col-md-5">
                                            <label for="txtFName" class="mb-2">First Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="First Name" id="txtFName" name="firstName" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="txtLName" class="mb-2">Last Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Last Name" id="txtLName" name="lastName" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-5">
                                        <div class="col-md-5">
                                            <label for="txtUsername" class="mb-2">Username</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Username" id="txtUsername" name="userName" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="txtPassword" class="mb-2">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Password" id="txtPassword" name="password" required>
                                                <span class="input-group-text">
                                                    <i class="bi bi-eye-slash-fill" id="btnShowHide" onclick="password_show_hide()"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="contact mt-3">
                                <span class="fs-4 red-font">Contact Information</span>
                                <hr>
                                <div class="mx-4">
                                    <div class="row g-5 mb-4">
                                        <div class="col-md-5">
                                            <label for="txtEmail" class="mb-2">Email Address</label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" placeholder="Email" id="txtEmail" name="email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="txtContact" class="mb-2">Contact Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Contact No." id="txtContact" name="contact" required onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>