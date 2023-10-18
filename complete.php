<?php
session_start();

// Redirects back to home when there is no account logged in
if (!(isset($_SESSION["accountID"]))) {
    header("location: ../FilmHouse");
    exit();
    // Redirects back to home when page is access manually in the address bar
} else if (!(isset($_GET["transaction"]))) {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmHouse Theater</title>

    <link rel="icon" type="image/png" href="img/logo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/all.css">
    <style>
        .navbar-light .navbar-nav .nav-link.nav-showing {
            color: #FC0013;
        }

        .complete {
            font-size: 70px;
        }

        .receipt {
            height: 220px;
        }

        .receipt-div {
            border-right: 10px solid #FC0013;
        }

        .complete-nav {
            display: flex;
            flex-flow: column;
            justify-content: center;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php require_once 'header.php' ?>
    <br>
    <div class="d-flex flex-column">
        <h1 class="text-center text-light pt-lg-5 h-showing complete">TRANSACTION COMPLETE</h1>
        <span class="date-now text-center" id="dateNow"></span>
    </div>

    <section class="p-5">
        <div class="container px-5">
            <div class="d-flex justify-content-center">
                <div class="px-5 receipt-div">
                    <img src="img/receipt.png" class="receipt"><br>
                    <button type="button" class="btn btnTickets w-100" onclick="printReceipt()">PRINT RECEIPT</button>
                </div>
                <div class="px-5 complete-nav">
                    <button type="button" class="btn btnTickets" onclick="goHome()">Go back to Home</button>
                    <br>
                    <p class="fs-1 text-light text-center">OR</p>
                    <button type="button" class="btn btnTickets" onclick="goTicket()">Process another ticket</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script>
        var date = new Date();
        var dateNowDisplay = document.getElementById("dateNow");
        const month = date.toLocaleString('default', {
            month: 'long'
        });

        var dateNow = `${month} ${date.getDate()}, ${date.getFullYear()}`;
        dateNowDisplay.innerText = dateNow;

        function printReceipt() {
            alert("Receipt has been printed.");
        }

        function goHome() {
            open("index.php", "_self");
        }

        function goTicket() {
            open("tickets.php", "_self");
        }
    </script>
</body>

</html>