<?php
session_start();

// Redirects back to home when there is no account logged in
if (!(isset($_SESSION["accountID"]))) {
    header("location: ../FilmHouse");
    exit();
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
    <?php require_once 'header.php';
    require_once 'includes/accounts.inc.php';
    // Retrieve accounts from database
    $accounts = retrieveAccounts();
    ?>
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
                        <div class="d-flex align-items-center page-title">
                            <span class="dashboard-icon"><i class="bi bi-people-fill"></i></span>
                            <span> Manage Acounts</span>
                        </div>
                        <br>
                        <form action="includes/accounts.inc.php" method="POST" onsubmit="return checkAccountSelect()">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-all shadow-none" type="checkbox" id="chkselectAll" onclick="selectAll()">
                                    <label class="form-check-label" for="chkselectAll">Select All</label>
                                </div>
                                <div class="d-flex">
                                    <button type="button" class="btn shadow-none rounded-3 button-crud" onclick="addAccount()">
                                        <i class="bi bi-plus d-flex"></i></button>
                                    <button type="submit" class="btn shadow-none ms-2 rounded-3 button-crud" id="btnDelete" name="delete-account">
                                        <i class="bi bi-trash d-flex"></i></button>
                                </div>
                            </div>
                            <table class="table table-hover" id="accounts-table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact No.</th>
                                        <th scope="col">Access Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $accounts->fetch_assoc()) : ?>
                                        <tr>
                                            <td><input class="form-check-input rowCheck shadow-none" type="checkbox" name="acctID[]" value="<?php echo $row['acctID']; ?>"></td>
                                            <td><?php echo $row['acctFName']; ?></td>
                                            <td><?php echo $row['acctLName']; ?></td>
                                            <td><?php echo $row['acctUsername']; ?></td>
                                            <td class="email"><?php echo $row['acctEmail']; ?></td>
                                            <td><?php echo $row['acctContact']; ?></td>
                                            <td><?php echo $row['accessType']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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