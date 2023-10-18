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
    <script src="js/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Navigation Bar -->
    <?php require_once 'header.php';
    require_once 'includes/transactions.inc.php';
    // Retrieve transactions from table
    $transactions = retrieveTransactions();
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
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnAccounts">Manage Accounts</button>
                                <button type="button" class="list-group-item list-group-item-action py-3 active button-nav" id="btnTransactions">Manage Transactions</button>
                            <?php else : ?>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav disabled" id="btnAccounts">Manage Accounts</button>
                                <button type="button" class="list-group-item list-group-item-action py-3 active button-nav disabled" id="btnTransactions">Manage Transactions</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm px-4 text-light">
                    <div class="dashboard">
                        <div class="d-flex align-items-center page-title">
                            <span class="dashboard-icon"><i class="bi bi-file-spreadsheet"></i></span>
                            <span> Manage Transactions</span>
                        </div>
                        <br>
                        <form action="includes/transactions.inc.php" method="POST" onsubmit="return checkTransactionSelect()">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input check-all shadow-none" type="checkbox" id="chkselectAll" onclick="selectAllTransaction()">
                                    <label class="form-check-label" for="chkselectAll">Select All</label>
                                </div>
                                <div class="d-flex">
                                    <button type="submit" class="btn shadow-none ms-2 rounded-3 button-crud" id="btnDelete" name="delete-transaction">
                                        <i class="bi bi-trash d-flex"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <table class="table table-hover" id="transactions-table">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col" class="align-middle">Transaction No.</th>
                                                <th scope="col" class="align-middle">Date & Time</th>
                                                <th scope="col" class="align-middle">Method</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $transactions->fetch_assoc()) : ?>
                                                <tr class="">
                                                    <td class="align-middle"><input class="form-check-input rowCheckThis shadow-none" type="checkbox" name="OR[]" value="<?php echo $row['transactionNo']; ?>"></td>
                                                    <td class="align-middle table-row" class="nr"><?php echo $row['transactionNo']; ?></td>
                                                    <td class="align-middle table-row"><?php echo $row['date_time']; ?></td>
                                                    <td class="align-middle table-row"><?php echo $row['method_of_payment']; ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md">
                                    <div class="card rounded-3 transaction-details">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Movie Title</p>
                                                <p class="label-value label-movie"></p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Room Number:</p>
                                                <p class="label-value label-room"></p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Screening Time:</p>
                                                <p class="label-value label-time"></p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Tickets:</p>
                                                <p class="label-value label-ticket"></p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Seats:</p>
                                                <p class="label-value label-seat"></p>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Total Amount:</p>
                                                <p class="label-value label-total"></p>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="label">Payment:</p>
                                                <p class="label-value label-payment"></p>
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