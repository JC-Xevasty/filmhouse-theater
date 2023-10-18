<?php

if (isset($_POST['edit'])) {
    header("location: ../manage-schedules.php?edit=".$_POST['roomID'][0]);    
}

if (isset($_GET["clear"])){
    clearSeats($_GET["room"], $_GET["clear"]);
}

if (isset($_POST['save'])){
    updateRoom();
}

// Retrieve Screening Times when Room is selected
if (isset($_POST['changeRoom'])){
    require 'dbh.inc.php';

    $sql = "SELECT *, TIME_FORMAT(screening_time, '%h:%i %p') formattedTime FROM seat_status WHERE roomID = ".$_POST['changeRoom'].";";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $schedules = array();

    while ($row = $resultData->fetch_assoc()) {
        $schedules[] = $row;
    }

    echo json_encode($schedules);
}

// Retrieve all Schedules
function retrieveSchedules()
{
    require_once 'dbh.inc.php';

    $sql = "SELECT screening_room.*, movies.movieTitle FROM screening_room JOIN movies ON screening_room.movieID = movies.movieID";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Retrieve Room details by ID
function retrieveRoomByID ($roomID){
    require 'dbh.inc.php';

    $sql = "SELECT * FROM screening_room WHERE roomID = ".$roomID.";";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $row = $resultData->fetch_assoc();
    return $row;
}

// Retrieve Room Schedules by ID
function retrieveRoomSchedules($roomID){
    require 'dbh.inc.php';

    $sql = "SELECT *, TIME_FORMAT(screening_time, '%h:%i %p') formattedTime FROM seat_status WHERE roomID = ".$roomID.";";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Clear Seat Capacity on database
function clearSeats($roomID, $time){
    require 'dbh.inc.php';

    $sql = "UPDATE seat_status SET no_of_available = 55, occupied='[]' WHERE roomID = ? AND screening_time = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $roomID, $time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../manage-schedules.php?edit=".$roomID);
    exit();
}

// Update Room details from database
function updateRoom(){
    require_once 'dbh.inc.php';
    
    $set = "movieID=?, seat_capacity=?";
    $sql = "UPDATE screening_room SET " . $set . " WHERE roomID = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }
    $roomId = $_POST["rooms"];
    $updMovie = $_POST["movies"];
    $updSeat = $_POST["seat"];

    mysqli_stmt_bind_param($stmt, "iii", $updMovie, $updSeat, $roomId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("location: ../dashboard-schedules.php");
    exit();
}