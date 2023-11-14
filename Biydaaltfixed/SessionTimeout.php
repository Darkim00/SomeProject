<?php
session_start();

function checkSessionTimeout() {
    //if user logged in
    if (isset($_SESSION['Username'])) {
        $username = $_SESSION['Username'];

        if (isset($_SESSION['last_activity'])) {
            $current_time = time();
            $inactive_time = $current_time - $_SESSION['last_activity'];
            if ($inactive_time > 300) {
                session_unset();
                session_destroy();
                header("Location: Login.php");
                exit();
            }
        }

        $_SESSION['last_activity'] = time();

    } else {
        //redirect to login.php if not logged in
        header("Location: Login.php");
        exit();
    }
}
?>
