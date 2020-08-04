<?php
    session_start();

    if (!$_SESSION['userID']) {
        header("location: /login.php");
    } else {
        header("location: /dashboard.php");
    }
    die();
