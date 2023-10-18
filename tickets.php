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
    <title>Tickets & Seats</title>

    <link rel="icon" type="image/png" href="img/logo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/tickets.css">
    <link rel="stylesheet" href="css/all.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <style>
        .navbar-light .navbar-nav .nav-link.nav-tickets {
            color: #FC0013;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php require_once 'header.php';
    require_once 'includes/movies.inc.php';
    // Retrieve movies from database
    $movies = retrieveMovieDetails();
    ?>

    <br>
    <section class="mb-5">
        <div class="container">
            <h2 class="text-light cinema-label my-5">Tickets and Seats</h2>
            <form action="transaction.php" method="post" onSubmit="return checkForm();">
                <div class="row g-1 text-light">
                    <!-- Movie and Schedule -->
                    <div class="col-md-3 text-dark">
                        <div class="card p-2 h-100 ticket-info">
                            <!-- Movie -->
                            <select class="form-select mb-3" name="movies" id="movies">
                                <option disabled selected value="">Movie Title</option>
                                <?php while ($row = $movies->fetch_assoc()) : ?>
                                    <option value="<?php echo $row['movieID']; ?>"><?php echo $row['movieTitle']; ?></option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Room -->
                            <select class="form-select mb-3" name="rooms" id="rooms">
                                <option disabled selected value="">Cinema Room</option>
                            </select>
                            <!-- Schedule -->
                            <select class="form-select mb-2" name="times" id="times">
                                <option disable selected value="">Schedule</option>
                            </select>
                            <p class="m-2" id="ticketPrice"> Price:</p>
                            <p class="m-2"> Ticket Type:</p>

                            <!-- Ticket Type -->
                            <!-- Adult -->
                            <div class="d-flex justify-content-between align-items-center my-1">
                                <div class="form-check m-2">
                                    <input class="form-check-input shadow-none" type="checkbox" name="adultTicket" value="0" id="chkAdult" onclick="ticketTypeChk()">
                                    <label class="form-check-label" for="chkAdult">Adult</label>
                                </div>
                                <!-- Counter -->
                                <div class="card d-none counter" id="adultCounter">
                                    <div class="rounded-3 border-dark d-flex align-items-center">
                                        <button type="button" class="btn btn-sm shadow-none" id="adultMinus"><i class="bi bi-dash fs-5"></i></button>
                                        <p class="align-self-center" id="adultCountLabel"></p>
                                        <button type="button" class="btn btn-sm shadow-none" id="adultPlus"><i class="bi bi-plus fs-5"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!-- Children -->
                            <div class="d-flex justify-content-between align-items-center my-1">
                                <div class="form-check m-2">
                                    <input class="form-check-input shadow-none" type="checkbox" name="childTicket" value="0" id="chkChild" onclick="ticketTypeChk()">
                                    <label class="form-check-label" for="chkChild">Children</label>
                                </div>
                                <!-- Counter -->
                                <div class="card d-none counter" id="childCounter">
                                    <div class="rounded-3 border-dark d-flex align-items-center">
                                        <button type="button" class="btn btn-sm shadow-none" id="childMinus"><i class="bi bi-dash fs-5"></i></button>
                                        <p class="align-self-center" id="childCountLabel"></p>
                                        <button type="button" class="btn btn-sm shadow-none" id="childPlus"><i class="bi bi-plus fs-5"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!-- Senior -->
                            <div class="d-flex justify-content-between align-items-center my-1">
                                <div class="form-check m-2">
                                    <input class="form-check-input shadow-none" type="checkbox" name="seniorTicket" value="0" id="chkSenior" onclick="ticketTypeChk()">
                                    <label class="form-check-label" for="chkSenior">Senior</label>
                                </div>
                                <!-- Counter -->
                                <div class="card d-none counter" id="seniorCounter">
                                    <div class="rounded-3 border-dark d-flex align-items-center">
                                        <button type="button" class="btn btn-sm shadow-none" id="seniorMinus"><i class="bi bi-dash fs-5"></i></button>
                                        <p class="align-self-center" id="seniorCountLabel"></p>
                                        <button type="button" class="btn btn-sm shadow-none" id="seniorPlus"><i class="bi bi-plus fs-5"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!-- PWD -->
                            <div class="d-flex justify-content-between align-items-center my-1">
                                <div class="form-check m-2">
                                    <input class="form-check-input shadow-none" type="checkbox" name="pwdTicket" value="0" id="chkPWD" onclick="ticketTypeChk()">
                                    <label class="form-check-label" for="chkPWD">PWD</label>
                                </div>
                                <!-- Counter -->
                                <div class="card d-none counter" id="pwdCounter">
                                    <div class="rounded-3 border-dark d-flex align-items-center">
                                        <button type="button" class="btn btn-sm shadow-none" id="pwdMinus"><i class="bi bi-dash fs-5"></i></button>
                                        <p class="align-self-center" id="pwdCountLabel"></p>
                                        <button type="button" class="btn btn-sm shadow-none" id="pwdPlus"><i class="bi bi-plus fs-5"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Screen and Seats -->
                    <div class="col-sm">
                        <div class="screen text-dark d-flex justify-content-center align-items-center mb-3">SCREEN
                        </div>
                        <br><br>
                        <div class="d-flex justify-content-center m-3" id="rowA">
                            <div class="seat" id="A1">A1</div>
                            <div class="seat" id="A2">A2</div>
                            <div class="seat" id="A3">A3</div>
                            <div class="seat" id="A4">A4</div>
                            <div class="seat" id="A5">A5</div>
                            <div class="seat" id="A6">A6</div>
                            <div class="seat" id="A7">A7</div>
                            <div class="seat" id="A8">A8</div>
                            <div class="seat" id="A9">A9</div>
                            <div class="seat" id="A10">A10</div>
                            <div class="seat" id="A11">A11</div>
                        </div>
                        <div class="d-flex justify-content-center m-3" id="rowB">
                            <div class="seat" id="B1">B1</div>
                            <div class="seat" id="B2">B2</div>
                            <div class="seat" id="B3">B3</div>
                            <div class="seat" id="B4">B4</div>
                            <div class="seat" id="B5">B5</div>
                            <div class="seat" id="B6">B6</div>
                            <div class="seat" id="B7">B7</div>
                            <div class="seat" id="B8">B8</div>
                            <div class="seat" id="B9">B9</div>
                            <div class="seat" id="B10">B10</div>
                            <div class="seat" id="B11">B11</div>
                        </div>
                        <div class="d-flex justify-content-center m-3" id="rowC">
                            <div class="seat" id="C1">C1</div>
                            <div class="seat" id="C2">C2</div>
                            <div class="seat" id="C3">C3</div>
                            <div class="seat" id="C4">C4</div>
                            <div class="seat" id="C5">C5</div>
                            <div class="seat" id="C6">C6</div>
                            <div class="seat" id="C7">C7</div>
                            <div class="seat" id="C8">C8</div>
                            <div class="seat" id="C9">C9</div>
                            <div class="seat" id="C10">C10</div>
                            <div class="seat" id="C11">C11</div>
                        </div>
                        <div class="d-flex justify-content-center m-3" id="rowD">
                            <div class="seat" id="D1">D1</div>
                            <div class="seat" id="D2">D2</div>
                            <div class="seat" id="D3">D3</div>
                            <div class="seat" id="D4">D4</div>
                            <div class="seat" id="D5">D5</div>
                            <div class="seat" id="D6">D6</div>
                            <div class="seat" id="D7">D7</div>
                            <div class="seat" id="D8">D8</div>
                            <div class="seat" id="D9">D9</div>
                            <div class="seat" id="D10">D10</div>
                            <div class="seat" id="D11">D11</div>
                        </div>
                        <div class="d-flex justify-content-center m-3 px-4" id="rowE">
                            <div class="seat" id="E1">E1</div>
                            <div class="seat" id="E2">E2</div>
                            <div class="seat" id="E3">E3</div>
                            <div class="seat" id="E4">E4</div>
                            <div class="seat" id="E5">E5</div>
                            <div class="seat" id="E6">E6</div>
                            <div class="seat" id="E7">E7</div>
                            <div class="seat" id="E8">E8</div>
                            <div class="seat" id="E9">E9</div>
                            <div class="seat" id="E10">E10</div>
                            <div class="seat" id="E11">E11</div>
                        </div>
                        <div class="d-flex justify-content-center mt-4 px-4">
                            <div class="legend">
                                <div class="seat leg"></div>
                                <p>Available</p>
                            </div>
                            <div class="legend">
                                <div class="seat seat-selected leg"></div>
                                <p>Selected</p>
                            </div>
                            <div class="legend leg">
                                <div class="seat seat-occupied"></div>
                                <p>Occupied</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Seats -->
                    <div class="col-md-2 text-dark">
                        <div class="card h-10 seat-info">
                            <div class="card-header text-center" id="seatsAvailable" data-num="0">Seats Available: 0</div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-title">Seats Selected: </div>
                                    <div class="card-title" id="seatsSelected">0</div>
                                </div>
                                <ul class="list-group style-3" id="seatsList" name="seatsList">
                                </ul>
                            </div>
                            <div class="card-footer">
                                <button type='submit' class='btn w-100 rounded-pill mb-2 px-3 pt-1 pb-0 fs-4' id="btnProceed" name="ticket">PROCEED</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="js/tickets.js"></script>
</body>

</html>