<?php

if (isset($_POST['add'])) {
    if (isset($_FILES['poster']['error']) !== 4) {
        $fileDestination = uploadPoster();
    } else {
        $fileDestination = "img/no_poster.png";
    }
    createMovie($fileDestination);
}

if (isset($_POST['edit'])) {
    header("location: ../manage-movies.php?edit=" . $_POST['movieID'][0]);
}

if (isset($_POST['save-movie'])) {
    updateMovie();
}

if (isset($_POST["delete-movie"])) {
    deleteMovie();
}

// Retrieve Room wwhen Movie is selected
if (isset($_POST['movieID'])) {
    require_once 'dbh.inc.php';

    $sql = "SELECT screening_room.*, movies.ticketPrice FROM screening_room JOIN movies ON screening_room.movieID = movies.movieID WHERE screening_room.movieID = " . $_POST['movieID'] . ";";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $rooms = array();
    while ($row = $resultData->fetch_assoc()) {
        $rooms[] = $row;
    }

    echo json_encode($rooms);
}

// Retrieve Schedule wwhen Room is selected
if (isset($_POST['roomID'])) {
    require_once 'dbh.inc.php';

    $sql = "SELECT * FROM seat_status WHERE roomID = " . $_POST['roomID'] . ";";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    $times = array();
    while ($row = $resultData->fetch_assoc()) {
        $times[] = $row;
    }

    echo json_encode($times);
}

// Retrieve Seat status when Schedule is selected
if (isset($_POST['screeningTime'])) {
    require_once 'dbh.inc.php';

    $sql = "SELECT * FROM seat_status WHERE roomID = " . $_POST['room_ID'] . " AND screening_time = '" . $_POST['screeningTime'] . "';";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    $row = $resultData->fetch_assoc();
    
    echo (json_encode($row));
}

// Retrieve Movies with Room Number and Screening Time
function retrieveMovies()
{
    require_once 'dbh.inc.php';

    $sql = "SELECT screening_room.roomID, movies.* FROM screening_room JOIN movies ON screening_room.movieID = movies.movieID ORDER BY screening_room.roomID;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Retrieve all Movies from database
function retrieveMovieDetails()
{
    require_once 'dbh.inc.php';

    $sql = "SELECT * FROM movies;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Retrieve Movie details by ID
function retrieveMovieById($movieID)
{
    require 'dbh.inc.php';

    $sql = "SELECT * FROM movies WHERE movieID = " . $movieID . ";";
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

// Retrieve all Rooms
function retrieveRooms()
{
    require 'dbh.inc.php';

    $sql = "SELECT * FROM screening_room;";
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
function retrieveRoomTicket($roomID)
{
    require 'dbh.inc.php';

    $sql = "SELECT * FROM screening_room WHERE roomID = " . $roomID . ";";
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

// Retrieve Screening Times details by ID
function retrieveTimeTicket($roomID, $screening_time)
{
    require 'dbh.inc.php';

    $sql = "SELECT *, TIME_FORMAT(screening_time, '%h:%i %p') formattedTime FROM seat_status WHERE roomID = " . $roomID . " AND screening_time = '" . $screening_time . "';";
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

// Retrieve Screening Time by ID
function retrieveScreeningTime($roomID)
{
    require 'dbh.inc.php';

    $sql = "SELECT screening_room.roomID, TIME_FORMAT(screening_time, '%h:%i %p') screening_time FROM screening_room JOIN seat_status ON screening_room.roomID = seat_status.roomID
    WHERE screening_room.roomID = " . $roomID . ";";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return $resultData;
}

// Add Movie to database
function createMovie($posterDir)
{
    require 'dbh.inc.php';

    $sql = "INSERT INTO movies (movieTitle, movieRating, ticketPrice, posterDir) VALUES (?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    $newTitle = $_POST["movieTitle"];
    $newRating = $_POST["rating"];
    $newPrice = $_POST["ticketPrice"];
    $newPoster = $posterDir;

    mysqli_stmt_bind_param($stmt, "ssss", $newTitle, $newRating, $newPrice, $newPoster);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../dashboard-movies.php");
    exit();
}

// Poster Image Handler
function uploadPoster()
{
    require_once 'dbh.inc.php';

    $poster = $_FILES['poster'];

    $fileName = $poster['name'];
    $fileTmpName = $poster['tmp_name'];
    $fileSize = $poster['size'];
    $fileError = $poster['error'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    $newFileName = $_POST['movieTitle'];
    $newFileName = preg_replace('/[:-?\/\\\\|<>*"]/', "", $newFileName);
    $newFileName = preg_replace("! !", "-", $newFileName);
    $newFileName = strtolower($newFileName) . "." . $fileActualExt;

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) {
                $fileDestination = '../posters/' . $newFileName;
                $fileDir = 'posters/' . $newFileName;
                move_uploaded_file($fileTmpName, $fileDestination);
                return $fileDir;
            } else {
                if (isset($_POST['add'])){
                    header("location: ../manage-movies.php?error=2");
                } else if (isset($_POST['save-movie'])){
                    header("location: ../manage-movies.php?edit=".$_POST['save-movie']."&error=2");
                }
                exit();
            }
        } else {
            echo "</br> There is an error: ";
            print_r($fileError);
        }
    } else {
        if (isset($_POST['add'])){
            header("location: ../manage-movies.php?error=1");
        } else if (isset($_POST['save-movie'])){
            header("location: ../manage-movies.php?edit=".$_POST['save-movie']."&error=1");
        }
        exit();
    }
}

// Update Movie from database
function updateMovie()
{
    $posterDir = "";
    print_r($_POST);
    print_r($_FILES);
    exit();
    if ($_FILES['poster']['error'] === 0) {
        $posterDir = uploadPoster();
    } else {
        require 'dbh.inc.php';

        $sql = "SELECT posterDir FROM movies WHERE movieID = " . $_POST['save-movie'] . ";";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_stmt_error($stmt));
            exit();
        }

        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $row = $resultData->fetch_assoc();
        $posterDir = $row['posterDir'];
    }

    require 'dbh.inc.php';

    $set = "movieTitle=?, movieRating=?, ticketPrice=?, posterDir=?";
    $sql = "UPDATE movies SET " . $set . " WHERE movieID = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }
    $movieID = $_POST['save-movie'];
    $updmovieName = $_POST["movieTitle"];
    $updmovieRating = $_POST["rating"];
    $updPrice = $_POST["ticketPrice"];

    mysqli_stmt_bind_param($stmt, "sssss", $updmovieName, $updmovieRating, $updPrice, $posterDir, $movieID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../dashboard-movies.php");

    exit();
}

// Remove Movie from database
function deleteMovie()
{
    require 'dbh.inc.php';

    $rowCount = count($_POST["movieID"]);
    $sql = "DELETE FROM movies WHERE movieID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_stmt_error($stmt));
        exit();
    }

    for ($i = 0; $i < $rowCount; $i++) {
        mysqli_stmt_bind_param($stmt, "i", $_POST["movieID"][$i]);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    header("location: ../dashboard-movies.php");
    exit();
}