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
    // Retrieve details about the movie to be edited
    if (isset($_GET["edit"])) {
        $movieDetail = retrieveMovieById($_GET["edit"]);
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
                            <button type="button" class="list-group-item list-group-item-action active py-3 button-nav" id="btnMovies">
                                Manage Movies</button>
                            <button type="button" class="list-group-item list-group-item-action py-3 button-nav" id="btnSchedules">
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
                        <form action="includes/movies.inc.php" method="POST" enctype="multipart/form-data">
                            <div class="d-flex align-items-center page-title">
                                <span class="dashboard-icon"><i class="bi bi-pencil-square"></i></span>
                                <span> Manage Movies</span>
                            </div>
                            <br>
                            <div class="d-flex align-items-center justify-content-between mb-2 profile-header">
                                <?php if (isset($_GET['edit'])) : ?>
                                    <span class="">Edit Movie</span>
                                    <div>
                                        <button type="button" class="btn btnCancel-movie rounded-3 mx-2" id="btnCancel" name="cancel" onclick="cancelEditMovie()">Cancel</button>
                                        <button type="submit" class="btn btnAdd-movie rounded-3" id="btnSaveMovie" name="save-movie" value="<?php echo $_GET["edit"]; ?>">Save Changes</button>
                                    </div>
                                <?php else : ?>
                                    <span class="">Add Movie</span>
                                    <div>
                                        <button type="button" class="btn btnCancel-movie rounded-3 mx-2" id="btnCancel" name="cancel" onclick="cancelAddMovie()">Cancel</button>
                                        <button type="submit" class="btn btnAdd-movie rounded-3" id="btnAdd" name="add">Add Movie</button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="personal mt-3 mb-5">
                                <hr>
                                <?php if (isset($_GET['error'])) : ?>
                                    <span class="fs-5 red-font d-flex justify-content-end error-display" id="movieError">
                                        <?php if ($_GET['error'] == 1) : ?>
                                            *Invalid image type
                                        <?php elseif ($_GET['error'] == 2) : ?>
                                            *File size too big
                                        <?php endif; ?>
                                    </span>
                                <?php else : ?>
                                    <span class="fs-5 red-font d-flex justify-content-end error-display d-none" id="movieError"></span>
                                <?php endif; ?>
                                <div class="mx-4">
                                    <div class="row g-5 mb-4">
                                        <div class="col-md-4">
                                            <div class="card bg-dark text-light border-0 mb-2">
                                                <?php if (isset($_GET['edit'])) : ?>
                                                    <img src="<?php echo $movieDetail['posterDir']; ?>" alt="" class="card-img" id="imgPoster">
                                                <?php else : ?>
                                                    <img src="img/no_poster.png" alt="" class="card-img" id="imgPoster">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="txtTitle" class="mb-3">Movie Title</label>
                                            <div class="input-group mb-4">
                                                <?php if (isset($_GET['edit'])) : ?>
                                                    <input type="text" class="form-control" placeholder="Movie Title" id="txtTitle" value="<?php echo $movieDetail['movieTitle']; ?>" name="movieTitle" required>
                                                <?php else : ?>
                                                    <input type="text" class="form-control" placeholder="Movie Title" id="txtTitle" value="" name="movieTitle" required>
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-flex w-100 mb-4">
                                                <div class="w-100 me-3">
                                                    <label class="mb-2">Movie Rating</label>
                                                    <select class="form-select rounded-3 mb-2" name="rating" required>
                                                        <?php if (isset($_GET['edit'])) : ?>
                                                            <option value="" disabled>Rating</option>
                                                            <option value="G" <?php if ($movieDetail['movieRating'] == "G") : echo "selected";
                                                                                endif; ?>>G</option>
                                                            <option value="PG-13" <?php if ($movieDetail['movieRating'] == "PG-13") : echo "selected";
                                                                                    endif; ?>>PG-13</option>
                                                            <option value="R-18" <?php if ($movieDetail['movieRating'] == "R-18") : echo "selected";
                                                                                    endif; ?>>R-18</option>
                                                        <?php else : ?>
                                                            <option value="" disabled selected>Rating</option>
                                                            <option value="G">G</option>
                                                            <option value="PG-13">PG-13</option>
                                                            <option value="R-18">R-18</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="w-100 ms-3">
                                                    <label for="txtPrice" class="mb-2">Ticket Price</label>
                                                    <div class="input-group">
                                                        <?php if (isset($_GET['edit'])) : ?>
                                                            <input type="number" class="form-control text-end" placeholder="0.00" id="txtPrice" value="<?php echo $movieDetail['ticketPrice']; ?>" name="ticketPrice" required onkeypress="return isPrice(event)">
                                                        <?php else : ?>
                                                            <input type="number" class="form-control text-end" placeholder="0.00" id="txtPrice" name="ticketPrice" required onkeypress="return isPrice(event)">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <label class="mb-3">Movie Poster</label>
                                            <input type="file" class="form-control filestyle" name="poster" onchange="loadPoster(event)" id="posterInput">
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
</body>

</html>