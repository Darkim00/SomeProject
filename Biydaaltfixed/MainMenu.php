<?php
require_once 'SessionTimeout.php';
checkSessionTimeout();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Main Menu</title>
  <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.15.3/css/all.css'><link rel="stylesheet" href="MainMenuStyle.css">

</head>
<body>
<h3 class="arroww" class = "fas fa-arrow-alt-right">
<?php

if (isset($_SESSION['Username'])) {
    $username = $_SESSION['Username'];
    
    echo "Welcome, $username!";
} else {
    // Redirect to login page if not logged in
    header("Location: Login.php");
    exit();
}
?>
</h3>
<div id = "menu">
<ol>
  <a class="arrow" href = "FlappyBird/index.php"><i class="fas fa-arrow-alt-right"></i>Start</a>
  <a class="arrow" href = "Leaderboard.php"><i class="fas fa-arrow-alt-right"></i>Leaderboard</a>
  <a class="arrow" href = "AboutUs.html"><i class="fas fa-arrow-alt-right"></i>About Us</a>
  <a class="arrow" href = "Logout.php"><i class="fas fa-arrow-alt-right"></i>Log Out</a>
</ol>
</div>
<div id="ground"></div>
  
</body>
</html>
