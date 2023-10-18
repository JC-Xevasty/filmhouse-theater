<?php
session_start();
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

    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/all.css">
    <style>
        .navbar-light .navbar-nav .nav-link.nav-about {
            color: #FC0013;
        }
    </style>
</head>

<body class="vh-100">
    <!-- Navigation Bar -->
    <?php require_once 'header.php' ?>
    <br>
    <section class="mb-5">
        <div class="container">
            <div class="d-flex justify-content-between my-5">
                <h2 class="text-light about-label">About</h2>
                <h2 class="text-light filmhouse-label">Filmhouse Theater Ticketing System</h2>
            </div>

            <div class="row about mx-5">
                <p>FilmHouse Theater Ticketing System is designed to provide a convenient and efficient system that will help the
                    cinema to perform its transactions and services to cater its users (managers, employees) and to satisfy the
                    customers with faster processing of their tickets. It also provides different modules for managing movies,
                    schedules, accounts, and transactions.</p>

                <p>Supported Browsers:</p>
                <ul class="list">
                    <li>Google Chrome (latest version)</li>
                    <li>Microsoft Edge (latest version)</li>
                    <li>Safari (latest version)</li>
                    <li>Mozilla Firefox (latest version)</li>
                    <li>Opera (latest version)</li>
                </ul>
                <p>The following tools and techologies were used to create the FilmHouse Theater Ticketing System:</p>
                <ul class="list">
                    <li>Bootstrap 5</li>
                    <li>jQuery 3.6.0 and JavaScript</li>
                    <li>PHP and MySQL database</li>
                </ul>
                <p>If you have any suggestions, issues or problems encountered while using the system's modules, please let us know
                    by referring to the contact information below.
                </p>
                <p>
                    <span>Email: sillydev09@gmail.com</span> <br>
                    <span>Phone: 09085624364 / (02) 88483219</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
<?php require_once 'footer.php' ?>
</html>