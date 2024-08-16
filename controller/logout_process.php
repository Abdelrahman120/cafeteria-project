<?php

session_start();
if ($_SESSION['login']) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../login_form.php");
}
