<nav class="navbar navbar-expand-md bg-light navbar-light py-3 fixed-top" style="box-shadow: 0 10px #FC0013;">
    <div class="container">
        <!-- Navbar Logo -->
        <a href="../FilmHouse/" class="navbar-brand"><img src="img/logo_nav.png" height="50"></a>

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="../FilmHouse/" class="nav-link mx-2 fs-4 nav-showing">NOW SHOWING</a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION["accountID"])) : ?>
                        <a href='tickets.php' class='nav-link mx-2 fs-4 nav-tickets'>TICKETS AND SEATS</a>
                    <?php else : ?>
                        <a href='#' class='nav-link mx-2 fs-4' data-bs-toggle='modal' data-bs-target='#loginModal'>TICKETS AND SEATS</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a href="about.php" class="nav-link mx-2 fs-4 nav-about">ABOUT</a>
                </li>
            </ul>
        </div>

        <?php if (isset($_SESSION["accountID"])) : ?>
            <div class='dropdown'>
                <button class='btn btnUser dropdown-toggle rounded-pill d-inline-flex align-items-center mx-2' type='button' id='btnLogin' data-bs-toggle='dropdown'>
                    <i class='bi bi-person-circle fs-4 d-flex me-1'></i>
                    <span class='fs-6 mx-1 align-self-center' id='lblUser'><?php echo $_SESSION["accountUser"]; ?></span>
                </button>
                <ul class='dropdown-menu dropdown-menu-end dropdown-menu-dark accountDropdown'>
                    <li><a class='dropdown-item' href='dashboard.php'>Dashboard</a></li>
                    <li><a class='dropdown-item' href='includes/logout.inc.php'>Logout</a></li>
                </ul>
            </div>
        <?php else : ?>
            <button type='button' class='btn btnLogin rounded-pill mx-2 px-5 pt-1 pb-0 fs-4' data-bs-toggle='modal' data-bs-target='#loginModal' id="btnLoginHeader"> Login</button>
        <?php endif; ?>
    </div>
</nav>