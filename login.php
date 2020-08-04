<?php
    session_start();
    if ($_SESSION['userID']) {
        header("location: /dashboard.php");
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        require_once 'db.php';
        $login = $conn->real_escape_string($_POST['login']);
        $password = $conn->real_escape_string($_POST['password']);
        $sql = "SELECT * from `users` WHERE `login` = '" . $login . "' AND `password` = '" . md5($password) . "'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['userID'] = $row['id'];
            header("location: /dashboard.php");
        } else {
            $_SESSION['error'] = 'Invalid login or password';
            header("Refresh: 0");
        }
    } else if ($_SESSION['error']) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    }

?>

<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="/assets/css/login.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700">
    </head>
    <body>
        <div id="login">
            <form method="POST">
                <span class="fontawesome-user"></span>
                <input type="text" id="user" placeholder="Username" name="login">

                <span class="fontawesome-lock"></span>
                <input type="password" id"pass" placeholder="Password" name="password">

                <span class="errorMessage"><?php echo $error; ?></span>
                <input type="submit" value="Login">
            </form>
        </div>
    </body>
</html>
