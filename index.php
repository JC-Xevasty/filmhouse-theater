<?php
session_start();

// Shows error message when user entered invalide credentials
$show_modal = false;
if (isset($_GET['error'])) {
    $show_modal = true;
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
    <script src="js/jquery-3.6.0.min.js"></script>
    <style>
        .navbar-light .navbar-nav .nav-link.nav-showing {
            color: #FC0013;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php require_once 'header.php';
    require_once 'includes/movies.inc.php';
    // Retrieve movies with details and screening times
    $movies = retrieveMovies();
    $moviedetail = array();

    while ($row = $movies->fetch_assoc()) :
        array_push($moviedetail, $row);
    endwhile;
    ?>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-light">
                    <span class="modal-title text-center w-100">Account Login</span>
                </div>
                <div class="modal-body">
                    <form action="includes/login.inc.php" method="POST" onsubmit="return checkForm()">
                        <p class="text-center">To continue, please log in</p>
                        <div class="d-flex justify-content-between">
                            <label for="txtUsername" class="mb-2">Username</label>
                            <?php if (isset($_GET['error'])) : ?>
                                <span class="red-font">*Invalid credentials</span>
                            <?php else : ?>
                                <span class="red-font d-none">Invalid credentials</span>
                            <?php endif; ?>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-person-fill fs-5"></i>
                            </span>
                            <input type="text" class="form-control shadow-none" placeholder="Username" id="txtUsername" name="loginUser">
                        </div>
                        <br>
                        <label for="txtPassword" class="mb-2">Password</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-lock-fill fs-5"></i>
                            </span>
                            <input type="password" class="form-control shadow-none" placeholder="Password" id="txtPassword" name="loginPass">
                            <span class="input-group-text" onclick="password_show_hide()">
                                <i class="bi bi-eye-slash-fill fs-5" id="btnShowHide"></i></span>
                        </div>
                        <button class="btn btnLoginModal mt-5 mb-3 w-100 modalLogin" data-bs-dismiss="" name="login">LOGIN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex flex-column">
        <h1 class="text-center text-light pt-lg-5 h-showing">SHOWING TODAY</h1>
        <span class="date-now text-center" id="dateNow"></span>
    </div>
    <section class="p-5">
        <div class="container px-5">
            <h2 class="text-light cinema-label my-5">Cinema Rooms</h2>
            <div class="row g-5 mb-5 px-5">
                <?php
                for ($i = 0; $i < count($moviedetail); $i++) {
                    $movie = $moviedetail[$i];
                    if ($i == 3) { ?>
            </div>
            <div class="row g-5 my-5 px-5">
            <?php   } ?>
            <div class="col-md">
                <div class="card text-light border-0 position-relative">
                    <div class="position-absolute room-number">CM <?php echo $movie['roomID']; ?></div>
                    <img src="<?php echo $movie['posterDir']; ?>" alt="" class="card-img-top">
                    <div class="card-body mt-3 p-4 pt-0">
                        <div class="movie-title d-flex justify-content-center align-items-center">
                            <h5 class="card-title text-center"><?php echo $movie['movieTitle']; ?></h5>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="schedule">
                                    <ul class="text-center list-unstyled">
                                        <?php $screening_times = retrieveScreeningTime($movie['roomID']);
                                        while ($row = $screening_times->fetch_assoc()) : ?>
                                            <li><?php echo $row['screening_time']; ?></li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                    <span class="colPRT">Php <?php echo $movie['ticketPrice']; ?>.00</span>
                                    <?php if ($movie['movieRating'] == 'PG-13') {
                                        echo "<span class='bg-primary lblRating'>" . $movie['movieRating'] . "</span>";
                                    } else if ($movie['movieRating'] == 'R-18') {
                                        echo "<span class='bg-danger lblRating'>" . $movie['movieRating'] . "</span>";
                                    } else {
                                        echo "<span class='bg-success lblRating'>" . $movie['movieRating'] . "</span>";
                                    } ?>
                                    <button type="button" class="btn btn-outline-light btnTickets" value="<?php echo $movie['movieID']; ?>" onclick="getTicket()">TICKETS</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>
    <script>
        function getTicket() {
            open("tickets.php", "_self");
        }
    </script>
    <?php if ($show_modal) : ?>
        <script>
            $(document).ready(function() {
                $('#loginModal').modal('show');
            })
        </script>
    <?php endif; ?>
    <?php require_once 'footer.php' ?>
</body>
</html>