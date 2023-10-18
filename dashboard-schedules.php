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
    require_once 'includes/schedules.inc.php';
    // Retrieve schedules from database
    $schedules = retrieveSchedules();
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
                            <button type="button" class="list-group-item list-group-item-action active py-3 button-nav" id="btnSchedules">
                                Manage Schedules</button>
                            <?php if ($_SESSION['accountType'] == 'admin') : ?>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnAccounts">Manage Accounts</button>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnTransactions">Manage Transactions</button>
                            <?php else : ?>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav disabled" id="btnAccounts">Manage Accounts</button>
                                <button type="button" class="list-group-item list-group-item-action py-3 button-nav disabled" id="btnTransactions">Manage Transactions</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm px-4 text-light">
                    <div class="dashboard">
                        <div class="d-flex align-items-center page-title">
                            <span class="dashboard-icon"><i class="bi bi-clock-history"></i></span>
                            <span> Manage Schedules</span>
                        </div>
                        <form action="includes/schedules.inc.php" method="POST" onsubmit="return checkScheduleSelect()">
                            <div class="d-flex justify-content-end mb-3 align-items-center">
                                <div class="d-flex">
                                    <button type="submit" class="btn shadow-none ms-2 rounded-3 button-crud view-room" id="btnEdit" name="edit" value="edit" onclick="buttonClicked(this)">
                                        VIEW ROOM</i></button>
                                </div>
                            </div>
                            <table class="table table-hover" id="schedule-table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Room Name</th>
                                        <th scope="col">Seat Capacity</th>
                                        <th scope="col">Movie Screened</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $schedules->fetch_assoc()) : ?>
                                        <tr>
                                            <td><input class="form-check-input rowCheck shadow-none" type="checkbox" name="roomID[]" value="<?php echo $row['roomID']; ?>"></td>
                                            <td><?php echo $row['roomName']; ?></td>
                                            <td><?php echo $row['seat_capacity']; ?></td>
                                            <td><?php echo $row['movieTitle']; ?></td>
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