
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
<style>
    .error {color: #FF0000;}
</style>
<?php
$nameErr = $usernameErr= $emailErr = $genderErr = $numberErr = $passwordErr = "";
$Name = $Username = $Email = $Number = $Password = $Gender = "";
$resultMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Name"])) {
        $nameErr = "Name is required!";
    } else {
        $Name = da_input($_POST['Name']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $Name)) {
            $nameErr = "Only letters and white space is allowed!";
        }
    }

    if (empty($_POST["Username"])) {
        $usernameErr = "Username is required!";
    } else {
        $Username = da_input($_POST['Username']);
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $Username)) {
            $usernameErr = "Only letters, numbers and white space is allowed!";
        }
    }

    if(empty($_POST["Email"])) {
        $emailErr = "Email is required!";
    } else {
        $Email = da_input($_POST['Email']);
        if(!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format!";
        }
    }

    if(empty($_POST["Number"])) {
        $numberErr = "Number is required!";
    } else {
        $Number = da_input($_POST['Number']);
        if(!is_numeric($Number)) {
            $numberErr = "Number only!";
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

    if(empty($_POST["Gender"])) {
        $genderErr = "Please choose gender!";
    } else {
        if (isset($_POST['Gender']) && !empty($_POST['Gender'])) {
            $Gender = $_POST['Gender'];
        }
    }
}

function da_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
if (empty($nameErr) && empty($usernameErr) && empty($emailErr) && empty($numberErr) && empty($passwordErr) && empty($genderErr)) {

    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', 'Darkimoo@312', 'test');
    if ($conn->connect_error) {
        die('Connection Failed Successfully: ' . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("INSERT INTO members (Username, Name, Email, Number, Password, Gender) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $Username, $Name, $Email, $Number, $hashedPassword, $Gender);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $resultMessage = "Registration Successful!";
        } else {
            $resultMessage = "Registration Failed!";
        }

        $stmt->close();
        $conn->close();
        }
}
?>
    <meta charset="UTF-8">
    <title> Register </title>
    <link rel="stylesheet" href="RegisterStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="title">Registration</div>
        <div class="content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Full Name</span>
                        <input type="text" placeholder="Enter your name" id="Name" name="Name" required>
                        <span class="error"> <?php echo $nameErr;?></span>
                    </div>
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" placeholder="Enter your username" id="Username" name="Username" required>
                        <span class="error"> <?php echo $usernameErr;?></span>
                    </div>
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="text" placeholder="Enter your email" id="Email" name="Email" required>
                        <span class="error"> <?php echo $emailErr;?></span>
                    </div>
                    <div class="input-box">
                        <span class="details">Phone Number</span>
                        <input type="text" placeholder="Enter your number" id="Number" name="Number" required>
                        <span class="error"> <?php echo $numberErr;?></span>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" placeholder="Enter your password" name="Password" required>
                        <span class="error"> <?php echo $passwordErr?> </span>
                    </div>
                </div>
                <div class="gender-details">
                    <input type="radio" name="Gender" id="dot-1" value="m" >
                    <input type="radio" name="Gender" id="dot-2" value="f" >
                    <input type="radio" name="Gender" id="dot-3" value="n" >
                    <span class="gender-title">Gender</span>
                    <div class="category">
                        <label for="dot-1">
                            <span class="dot one"></span>
                            <span class="gender">Male</span>
                        </label>
                        <label for="dot-2">
                            <span class="dot two"></span>
                            <span class="gender">Female</span>
                        </label>
                        <label for="dot-3">
                            <span class="dot three"></span>
                            <span class="gender">Prefer Not Say</span>
                        </label>
                    </div>
                    <span class="error"> <?php echo $genderErr;?></span>
                </div>
                <div class="button">
                    <input type="submit" value="Register">
                </div>
                <div class="result-message">
                    <?php
                        // Check if the form was submitted
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            echo $resultMessage;
                        }
                    ?>
                </div>
                <div class="login-link">
                    Already have an account? <a href="Login.php">Login now</a>
                </div>
            </form>
        </div>
    </div>


</body>

</html>
