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
    require_once 'includes/movies.inc.php';
    require_once 'includes/schedules.inc.php';
    // Retrieve information necessary for managing schedules
    $movies = retrieveMovieDetails();
    $rooms = retrieveRooms();
    $roomID = $_GET["edit"];
    $times = retrieveRoomSchedules($roomID);

    if (isset($_GET["edit"])) {
        $roomDetail = retrieveRoomById($_GET["edit"]);
        $roomSchedules = retrieveRoomSchedules($_GET["edit"]);
    }
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
                        <form action="includes/schedules.inc.php" method="POST" enctype="multipart/form-data">
                            <div class="d-flex align-items-center page-title">
                                <span class="dashboard-icon"><i class="bi bi-pencil-square"></i></span>
                                <span> Manage Schedules</span>
                            </div>
                            <br>
                            <div class="d-flex align-items-center justify-content-between mb-2 profile-header">
                                <span class="">Edit Schedule</span>
                                <div>
                                    <button type="button" class="btn btnCancel-room rounded-3 mx-2" id="btnEdit" onclick="cancelEditRoom()">Cancel</button>
                                    <button type="submit" class="btn btnSave-room rounded-3" id="btnSave" name="save">Save Changes</button>
                                </div>
                            </div>
                            <div class="personal mt-3 mb-5">
                                <hr>
                                <div class="mx-4">
                                    <div class="row g-5 mb-4">
                                        <div class="col-md-4">
                                            <div class="mb-4">
                                                <label class="mb-2">Room Name</label>
                                                <select class="form-select rounded-3 mb-2" name="rooms" id="roomName" onchange="roomChange()">
                                                    <?php while ($row = $rooms->fetch_assoc()) : ?>
                                                        <?php if ($row['roomID'] == $roomID) : ?>
                                                            <option value="<?php echo $row['roomID']; ?>" selected><?php echo $row['roomName']; ?></option>
                                                        <?php else : ?>
                                                            <option value="<?php echo $row['roomID']; ?>"><?php echo $row['roomName']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="mb-2">Movie To be Screened</label>
                                                <select class="form-select rounded-3 mb-2" name="movies">
                                                    <?php while ($row = $movies->fetch_assoc()) : ?>
                                                        <?php if ($row['movieID'] == $roomDetail['movieID']) : ?>
                                                            <option value="<?php echo $row['movieID']; ?>" selected><?php echo $row['movieTitle']; ?></option>
                                                        <?php else : ?>
                                                            <option value="<?php echo $row['movieID']; ?>"><?php echo $row['movieTitle']; ?></option>
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <label for="txtRoomName" class="mb-3 w-100 me-3">Seat Capacity</label>
                                                <div class="input-group mb-4 w-30">
                                                    <?php if (isset($_GET['edit'])) : ?>
                                                        <input type="number" class="form-control text-end" placeholder="0" id="txtRoomName" value="<?php echo $roomDetail['seat_capacity']; ?>" name="seat" required onkeypress="return isNumberKey(event)">
                                                    <?php else : ?>
                                                        <input type="number" class="form-control text-end" placeholder="55" id="txtRoomName" value="" name="seat" required onkeypress="return isNumberKey(event)">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <label class="mb-2">Schedule / Time Slots</label>
                                            <table class="table table-hover" id="schedule-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"></th>
                                                        <th scope="col">Screening Time</th>
                                                        <th scope="col">Available Seats</th>
                                                        <th scope="col">Clear Seats</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableBody">
                                                    <?php while ($row = $roomSchedules->fetch_assoc()) : ?>
                                                        <tr>
                                                            <td class="align-middle"><input class="form-check-input rowCheck shadow-none" type="hidden" name="screening_time[]" value="<?php echo $row['screening_time']; ?>"></td>
                                                            <td class="align-middle"><?php echo $row['formattedTime']; ?></td>
                                                            <td class="align-middle"><?php echo $row['no_of_available']; ?></td>
                                                            <td class="align-middle">
                                                                <button type="button" onclick="location.href='includes/schedules.inc.php?clear=<?php echo $row['screening_time']; ?>&room=<?php echo $roomID; ?>';" class="btn btnClear-seats rounded-3" id="btnSave" name="save">Clear</button>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
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
    <script type="text/javascript">
        function roomChange() {
            var select = document.getElementById('roomName');
            var roomID = select.options[select.selectedIndex].value
            open("manage-schedules.php?edit=" + roomID, "_self");
        }
    </script>
</body>

</html>