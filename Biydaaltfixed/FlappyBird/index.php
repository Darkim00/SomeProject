<?php
require_once '../SessionTimeout.php';
checkSessionTimeout();
if (isset($_SESSION['Username'])) {
    $username = $_SESSION['Username'];
    echo "Welcome $username!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Original Flappy Bird -JavaScript</title>
    <link href="https://fonts.googleapis.com/css?family=Teko:700" rel="stylesheet">
    <style>        
        canvas{
            border: 1px solid #000;
            display: block;
            margin: auto;
        }
    </style>
</head>
<body style="background-color:#FF994A;">
    <canvas id="bird" width="700" height="600"></canvas>

    <script src="game.js">
    </script>
    <script>
        var Username = "<?php echo $_SESSION['Username']; ?>"; // Pass the username to the game
    </script>

</body>
</html>
