<?php

if (isset($_POST['complete'])){
    createTransaction();
} else if (isset($_POST['delete-transaction'])){
    deleteTransaction();
} else if (isset($_POST['transNo'])){
    require 'dbh.inc.php';

    $sql = "SELECT transactions.*, movies.movieTitle, screening_room.roomName, TIME_FORMAT(transactions.screening_time, '%h:%i %p') formattedTime FROM transactions
    JOIN movies ON transactions.movieID = movies.movieID
    JOIN screening_room ON transactions.roomID = screening_room.roomID
    WHERE transactionNo ='" .$_POST['transNo']. "';";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $details = $resultData->fetch_assoc();

    echo json_encode($details);
}

// Compute Item and Total Amount
function computeAmount($ticket_price, $tickets) {
    $computation = array();
    $items = array();
    $base_price = $ticket_price;
    $total_amount = 0;
    foreach($tickets as $ticket){
        if ($ticket["type"] == "Adult"){
            $item_total = $base_price * $ticket["qty"];
            $total_amount = $total_amount + $item_total;
            $items[] = array("type"=>"Adult", "qty"=>$ticket["qty"], "item_base"=>$base_price, "item_total"=>$item_total);
        } else if ($ticket["type"] == "Children"){
            $item_base = $base_price - ($base_price * .2);
            $item_total = $item_base * $ticket["qty"];
            $total_amount = $total_amount + $item_total;
            $items[] = array("type"=>"Children", "qty"=>$ticket["qty"], "item_base"=>$item_base, "item_total"=>$item_total);
        } else if ($ticket["type"] == "Senior" || $ticket["type"] == "PWD"){
            $item_base = $base_price - ($base_price * .16);
            $item_total = $item_base * $ticket["qty"];
            $total_amount = $total_amount + $item_total;
            $items[] = array("type"=>$ticket["type"], "qty"=>$ticket["qty"], "item_base"=>$item_base, "item_total"=>$item_total);
        }
    }

    $tax = round(($total_amount * .12),2);
    $subtotal = round(($total_amount - $tax),2);
    $computation = array("base_price"=>$base_price, "items"=>$items, "tax"=>$tax, "subtotal"=>$subtotal, "total_amount"=>$total_amount);
    return $computation;
}

// Generate a unique Transaction Number
function generateTransactionNo(){
    $randnum = random_int(100000000, 999999999);
    $transNo = "OR".$randnum;

    require 'dbh.inc.php';

    $sql = "SELECT * FROM transactions WHERE transactionNo = '".$transNo."';";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }
    
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($resultData) == 0){
        return $transNo;
    } else {
        generateTransactionNo();
    }
}

// Retrieve all Transactions from database
function retrieveTransactions(){
    require_once 'dbh.inc.php';

    $sql = "SELECT transactions.*, movies.movieTitle, screening_room.roomName, TIME_FORMAT(transactions.screening_time, '%h:%i %p') formattedTime FROM transactions
    JOIN movies ON transactions.movieID = movies.movieID
    JOIN screening_room ON transactions.roomID = screening_room.roomID";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Retrieve current date and time
function getDateTime(){
    date_default_timezone_set("Asia/Hong_Kong");
    $date_time = date("M d, Y h:i A");
    return $date_time;
}

// Add Transaction to database
function createTransaction() {
    session_start();

    $ticketStr = "";
    foreach ($_SESSION['transaction_details']['tickets'] as $ticket) {
        $ticketStr = $ticketStr . "[" . $ticket["type"] . " X " . $ticket["qty"] . "] ";
    }

    $seatStr = "";
    foreach ($_SESSION['transaction_details']['seats'] as $seat) {
        $seatStr = $seatStr . "[" . $seat . "], ";
    }

    $transaction_details = $_SESSION['transaction_details'];
    $transNo = $transaction_details['transactionNo'];
    $date_time = $transaction_details['date_time'];
    $movieID = $transaction_details['movieID'];
    $roomID = $transaction_details['roomID'];
    $screening_time = $transaction_details['screening_time'];
    $tickets = $ticketStr;
    $seats = $seatStr;
    $total_amount = $transaction_details['total_amount'];
    $method_of_payment = $_POST['btnMethod'];
    $payment = $total_amount;
    if ($_POST['payment'] != "") {
        $payment = $_POST['payment'];
    }

    require 'dbh.inc.php';

    $fields = "(transactionNo, date_time, movieID, roomID, screening_time, tickets, seats, total_amount, method_of_payment, payment)";
    $sql = "INSERT INTO transactions ".$fields." VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssssssss", $transNo, $date_time, $movieID, $roomID, $screening_time, $tickets, $seats, $total_amount, $method_of_payment, $payment);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $newSeatsOccupied = $_SESSION['transaction_details']['seats'];
    updateOccupiedSeats ($roomID, $screening_time, $newSeatsOccupied);

    unset($_SESSION['transaction_details']);
    header("location: ../complete.php?transaction=success");
    exit();

}

// Update List of Occupied Seats on database
function updateOccupiedSeats ($roomID, $screening_time, $newSeats) {
    require 'dbh.inc.php';

    $sql = "SELECT * FROM seat_status WHERE roomID = " . $roomID . " AND screening_time = '" . $screening_time . "';";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    
    $row = $resultData->fetch_assoc();
    $currentSeatAvailable = $row['no_of_available'];
    $newSeatAvailable = $currentSeatAvailable - count($newSeats);
    
    $currentSeatsOccupied = json_decode($row['occupied']);
    
    foreach($newSeats as $newSeat) {
        $currentSeatsOccupied[] = $newSeat;
    }

    sort($currentSeatsOccupied);
    $newSeatsOccupied = json_encode($currentSeatsOccupied);

    $sql2 = "UPDATE seat_status SET no_of_available=?, occupied=? WHERE roomID = " . $roomID . " AND screening_time = '" . $screening_time . "';";
    $stmt2 = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        die(mysqli_stmt_error($stmt2));
        exit();
    }

    mysqli_stmt_bind_param($stmt2, "ss", $newSeatAvailable, $newSeatsOccupied);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
}

// Remove Transaction from database
function deleteTransaction () {
    require 'dbh.inc.php';

    $rowCount = count($_POST["OR"]);
    $sql = "DELETE FROM transactions WHERE transactionNo = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    for ($i = 0; $i < $rowCount; $i++){
        mysqli_stmt_bind_param($stmt, "s", $_POST["OR"][$i]);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);

    header("location: ../dashboard-transactions.php");
    exit();
}