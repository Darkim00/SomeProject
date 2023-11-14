<?php
session_start(); // Start the session

$usernameErr = "";
function da_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST["Username"])) {
        $usernameErr = "Username is required!";
    } else {
        $Username = da_input($_POST['Username']);
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $Username)) {
            $usernameErr = "Username only takes letters and numbers";
        }
    }

    if(empty($_POST["Password"])) {
        $passwordErr = "Password is required!";
    } else {
        $Password = da_input($_POST['Password']);
        $uppercase = preg_match('@[A-Z]@', $Password);
        $lowercase = preg_match('@[a-z]@', $Password);
        $number    = preg_match('@[0-9]@', $Password);
        if(!$uppercase || !$lowercase || !$number || strlen($Password) < 8) {
            $passwordErr = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number.';
        }
    }

    // Your database connection code here
    $conn = new mysqli('localhost', 'root', 'Darkimoo@312', 'test');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Prepare and execute a query to check the username
    $stmt = $conn->prepare("SELECT * FROM members WHERE Username = ?");
    $stmt->bind_param("s", $Username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User exists, fetch the row
        $row = $result->fetch_assoc();

        // Compare the hashed password from the database
        if (password_verify($Password, $row['Password'])) {
            // Login successful
            $_SESSION['Username'] = $Username; // Store the username in the session
            header("Location: MainMenu.php"); // Redirect to a menu page
            exit();
        } else {
            // Incorrect password
            $loginError = "Incorrect password.";
        }
    } else {
        // User not found
        $loginError = "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="LoginStyle.css">
</head>
<body>
    <div class="wrapper">
        <div class="title">
            Login Form
        </div>
        <form action="login.php" method="post">
            <div class="field">
                <input type="text" name="Username" required>
                <label>Username</label>
            </div>
            <div class="field">
                <input type="password" name="Password" required>
                <label>Password</label>
            </div>
            <?php
            // Display the login error message if it exists
            if (isset($loginError)) {
                echo '<div class="error-message">' . $loginError . '</div>';
            }
            if(isset($usernameErr)) {
                echo '<div class="error-message">' . $usernameErr . '</div>';
            }
            ?>
            <div class="content">
                <div class="checkbox">
                    <input type="checkbox" id="remember-me" name="remember_me">
                    <label for="remember-me">Remember me</label>
                </div>
                <div class="pass-link">
                    <a href="#">Forgot password?</a>
                </div>
            </div>
            <div class="field">
                <input type="submit" value="Login">
            </div>
            <div class="signup-link">
                Not a member? <a href="Register.php">Signup now</a>
            </div>
        </form>
    </div>
</body>
</html>
