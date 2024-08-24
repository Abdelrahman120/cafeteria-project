
<?php if ($_SESSION['login'] === true) {
        $session_email = $_SESSION['email'];
        $session_user = explode("@", $session_email);}

echo'
        <header>
        <nav class="navbar navbar-expand-lg navigation-bar">
            <div class="container-fluid">
                <a class="navbar-brand text-light p-1 fs-1 fw-bolder" href="fatoraOreder.php">Cafeteria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse nav-element d-flex align-item-center justify-content-between"
                    id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link text-light fs-4" href="fatoraOreder.php">Home</a>
                        <a class="nav-link text-light fs-4" href="order.view.php">My Orders</a>
                    </div>
                    <div class="user-info navbar-nav">
                        <div class="nav-link text-light fs-4"><i class="fa-regular fa-user"></i></div>
                        <a class="nav-link text-light fs-4" href="#"> '; echo "{$session_user[0]}"; echo '</a>
                        <a class="nav-link text-light fs-4" href="logout.php"><i
                                class="fa-solid fa-sign-out"></i></a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
';
?>