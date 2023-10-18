<?php
session_start();

// Redirects back to home when there is no account logged in
if (!(isset($_SESSION["accountID"]))) {
    header("location: ../FilmHouse");
    exit();
} else {
    // Continue to this page when access through the Tickets and Seats page
    if (isset($_POST["ticket"])) {
        $selected_movieID = $_POST["movies"];
        $selected_roomID = $_POST["rooms"];
        $screening_time = $_POST["times"];
        $seats = $_POST["seatsList"];
        $tickets = array();
        if (isset($_POST["adultTicket"])) {
            $tickets[] = array("type" => "Adult", "qty" => $_POST["adultTicket"]);
        }
        if (isset($_POST["childTicket"])) {
            $tickets[] = array("type" => "Children", "qty" => $_POST["childTicket"]);
        }
        if (isset($_POST["seniorTicket"])) {
            $tickets[] = array("type" => "Senior", "qty" => $_POST["seniorTicket"]);
        }
        if (isset($_POST["pwdTicket"])) {
            $tickets[] = array("type" => "PWD", "qty" => $_POST["pwdTicket"]);
        }
        // Redirects back to Tickets and Seats page when accessed manually in the address bar
    } else {
        header("location: tickets.php");
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
    <title>Transaction Summary</title>

    <link rel="icon" type="image/png" href="img/logo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/transaction.css">
    <link rel="stylesheet" href="css/all.css">
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
    // Retrieve details about the movie, room, and schedule from the database
    $movieDetail = retrieveMovieById($selected_movieID);
    $roomDetail = retrieveRoomTicket($selected_roomID);
    $timeDetail = retrieveTimeTicket($selected_roomID, $screening_time);
    
    require_once 'includes/transactions.inc.php';
    // Creating the transaction
    $date_time = getDateTime();
    $computation = computeAmount($movieDetail["ticketPrice"], $tickets);
    $transNo = generateTransactionNo();

    $transaction_details = array(
        'transactionNo' => $transNo,
        'date_time' => $date_time,
        'movieID' => $movieDetail['movieID'],
        'roomID' => $roomDetail['roomID'],
        'screening_time' => $timeDetail['screening_time'],
        'tickets' => $tickets,
        'seats' => $seats,
        'total_amount' => $computation["total_amount"]
    );

    $_SESSION['transaction_details'] = $transaction_details;
    ?>
    <br>
    <section class="mb-5">
        <div class="container px-5">
            <form action="includes/transactions.inc.php" method="POST" onsubmit="return checkForm()">
                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btnBack rounded-3 px-4 shadow-none" id="btnBack">Back</button>
                    <button type="button" class="btn btnCheckout rounded-3 px-4 shadow-none" id="btnCheckout" name="complete">Checkout</button>
                    <button type="submit" class="btn btnCheckout complete rounded-3 px-4 shadow-none d-none" id="btnComplete" name="complete">Complete Transaction</button>
                </div>
                <div class="transaction-container rounded-3" id="transaction-container">
                    <div class="summary-container">
                        <div class="row text-light g-0 rounded-top note">
                            <div class="col-md text-center">
                                <p class="m-2">Please review the details below. Proceed to Checkout to continue.</p>
                            </div>
                        </div>
                        <div class="row text-light g-0 rounded-bottom p-3">
                            <div class="col-md">
                                <div class="row g-0 rounded-bottom p-3">
                                    <div class="col-md-4 labels">
                                        <p>Date and Time:</p>
                                        <p>Cinema Name:</p>
                                        <p>Movie Title:</p>
                                        <p>Ticket Price:</p>
                                        <p>Ticket Type:</p>
                                        <p>Seats Taken:</p>
                                    </div>
                                    <div class="col-md">
                                        <p id="dateTime"><?php echo $date_time; ?></p>
                                        <p>FilmHouse Theater</p>
                                        <p><?php echo $movieDetail["movieTitle"]; ?></p>
                                        <p>Php <?php echo $movieDetail["ticketPrice"]; ?>.00 per ticket</p>
                                        <p>
                                            <?php $ticketStr = "";
                                            foreach ($tickets as $ticket) {
                                                $ticketStr = $ticketStr . "[" . $ticket["type"] . " X " . $ticket["qty"] . "] ";
                                            }
                                            echo $ticketStr;
                                            ?>
                                        </p>
                                        <p>
                                            <?php $seatStr = "";
                                            foreach ($seats as $seat) {
                                                $seatStr = $seatStr . "[" . $seat . "], ";
                                            }
                                            echo $seatStr;
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="row g-0 rounded-bottom p-3">
                                    <div class="col-md-4 labels">
                                        <p>Transaction No.:</p>
                                        <p>Cinema Room:</p>
                                        <p>Schedule Time:</p>
                                        <p>Amount Due:</p>
                                        <p class="pt-2">Amount Breakdown:</p>
                                    </div>
                                    <div class="col-md">
                                        <p><?php echo $transNo; ?></p>
                                        <p><?php echo $roomDetail["roomName"]; ?></p>
                                        <p><?php echo $timeDetail["formattedTime"]; ?></p>
                                        <p>Php <?php echo number_format($computation["total_amount"], 2); ?></p>
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <span>Ticket Price:</span>
                                                    <span>Php <?php echo number_format($computation["base_price"], 2); ?></span>
                                                </div>
                                            </div>
                                            <div class="card-body mx-2 pt-1">
                                                <ul class="list-group list-group-flush" id="seatsList">
                                                    <?php
                                                    $items = $computation["items"];
                                                    foreach ($items as $item) : ?>
                                                        <li class="list-group-item border-0 p-2 py-0">
                                                            <div class="d-flex justify-content-between">
                                                                <span><?php echo $item['type'] . " X " . $item['qty']; ?></span>
                                                                <span><?php echo number_format($item['item_total'], 2); ?></span>
                                                            </div>
                                                            <span class="ms-3">(@<?php echo number_format($item['item_base'], 2); ?>)</span>
                                                        </li>

                                                    <?php endforeach; ?>
                                                </ul>
                                                <br>
                                                <div class="d-flex justify-content-between">
                                                    <span>12% TAX</span>
                                                    <span><?php echo number_format($computation['tax'], 2); ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Subtotal</span>
                                                    <span><?php echo number_format($computation['subtotal'], 2); ?></span>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between">
                                                    <span>Amount Due</span>
                                                    <span>Php <?php echo number_format($computation["total_amount"], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment-container rounded-3">
                        <div class="row g-0 rounded-top note">
                            <div class="col-md text-center">
                                <p class="m-2">Choose method of payment.</p>
                            </div>
                        </div>
                        <div class="row text-light h-100 pb-3 g-0 rounded-bottom p-3">
                            <div class="col-md-6">
                                <div class="row g-0 p-3 pb-0">
                                    <div class="col-md-4 labels">
                                        <p>Movie Title:</p>
                                        <p>Cinema Room:</p>
                                        <p>Ticket Type:</p>
                                        <p>Seats Taken:</p>
                                        <p class="pt-2">Amount Breakdown:</p>
                                    </div>
                                    <div class="col-md">
                                        <p><?php echo $movieDetail["movieTitle"]; ?></p>
                                        <p><?php echo $roomDetail["roomName"]; ?></p>
                                        <p>
                                            <?php $ticketStr = "";
                                            foreach ($tickets as $ticket) {
                                                $ticketStr = $ticketStr . "[" . $ticket["type"] . " X " . $ticket["qty"] . "] ";
                                            }
                                            echo $ticketStr;
                                            ?>
                                        </p>
                                        <p>
                                            <?php $seatStr = "";
                                            foreach ($seats as $seat) {
                                                $seatStr = $seatStr . "[" . $seat . "], ";
                                            }
                                            echo $seatStr;
                                            ?>
                                        </p>
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <span>Ticket Price:</span>
                                                    <span>Php <?php echo number_format($computation["base_price"], 2); ?></span>
                                                </div>
                                            </div>
                                            <div class="card-body mx-2 pt-1">
                                                <ul class="list-group list-group-flush" id="seatsList">
                                                    <?php
                                                    $items = $computation["items"];
                                                    foreach ($items as $item) : ?>
                                                        <li class="list-group-item border-0 p-2 py-0">
                                                            <div class="d-flex justify-content-between">
                                                                <span><?php echo $item['type'] . " X " . $item['qty']; ?></span>
                                                                <span><?php echo number_format($item['item_total'], 2); ?></span>
                                                            </div>
                                                            <span class="ms-3">(@<?php echo number_format($item['item_base'], 2); ?>)</span>
                                                        </li>

                                                    <?php endforeach; ?>
                                                </ul>
                                                <br>
                                                <div class="d-flex justify-content-between">
                                                    <span>12% TAX</span>
                                                    <span><?php echo number_format($computation['tax'], 2); ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Subtotal</span>
                                                    <span><?php echo number_format($computation['subtotal'], 2); ?></span>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between">
                                                    <span>Amount Due</span>
                                                    <span>Php <?php echo number_format($computation["total_amount"], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md ms-5">
                                <div class="row p-3 px-5">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check rounded-3" name="btnMethod" id="btnCash" autocomplete="off" checked value="CASH">
                                        <label class="btn btnMethod p-2 d-flex justify-content-center shadow-none" for="btnCash">
                                            <i class="bi bi-cash fs-2 d-flex me-2"></i>
                                            <span class="paymethod mx-1 align-self-center">CASH</span>
                                        </label>
                                        <input type="radio" class="btn-check rounded-3" name="btnMethod" id="btnCard" autocomplete="off" value="CARD">
                                        <label class="btn btnMethod p-2 d-flex justify-content-center shadow-none" for="btnCard">
                                            <i class="bi bi-credit-card fs-2 d-flex me-2"></i>
                                            <span class="paymethod mx-1 align-self-center">CARD</span>
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="paymethod-container" id="paymethod-container">
                                    <div class="cash-container p-3">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Total Amount</span>
                                            <span id="lblTotal" data-total="<?php echo $computation["total_amount"]; ?>">Php <?php echo number_format($computation["total_amount"], 2); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="w-100">Cash Payment</span>
                                            <input type="number" class="form-control text-end" placeholder="0.00" id="payment" name="payment" onkeypress="return isNumberKey(event)">
                                        </div>
                                        <hr>
                                        <button type="button" class="btn btnCompute w-100 rounded-3 px-4 shadow-none" onclick="computeChange()">Compute</button>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Change</span>
                                            <span id="lblChange">Php 0.00</span>
                                        </div>
                                    </div>
                                    <div class="card-container p-3">
                                        <span>Accepted Credit / Debit Cards:</span>
                                        <br>
                                        <div class="d-flex">
                                            <div class="card me-1">
                                                <img src="img/visa.svg" height="25px" />
                                            </div>
                                            <div class="card">
                                                <img src="img/mastercard.svg" height="25px" />
                                            </div>
                                            <div class="card me-1">
                                                <img src="img/jcb.svg" height="25px" />
                                            </div>
                                            <div class="card">
                                                <img src="img/american.svg" height="25px" />
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center my-3">
                                            <span class="w-100">Cardholder's Name:</span>
                                            <input type="text" class="form-control" placeholder="Account Name" id="cardName">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="w-100">Card Number</span>
                                            <input type="number" class="form-control" placeholder="Card No." id="cardNo" onkeypress="return isNumberKey(event)">
                                        </div>
                                        <hr>
                                        <button type="button" class="btn btnCardCheckout w-100 rounded-3 px-4 shadow-none" onclick="completeCard()">Checkout</button>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="js/transaction.js"></script>
</body>

</html>