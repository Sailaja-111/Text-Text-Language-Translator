<?php
session_start();
require_once "database.php";

$errorMessage = "";

if(isset($_POST["submit"])) {
    $email = $_POST["email"];
    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "s", $email); 
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt); 
    
    if(mysqli_num_rows($result) > 0) {
        $otp = rand(100000, 999999);
        $_SESSION["otp"] = $otp;
        
        $to = $email;
        $subject = "Password Reset OTP";
        $message = "Your OTP for password reset is: $otp";
        $headers = "From: reddysiva57675@gmail.com \r\n"; 
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to, $subject, $message, $headers);
        
        header("Location: reset.php");
        exit;
    } else {
        $errorMessage = "Email does not exist";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Text Translation App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #fff;
        }

        h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .form-container {
            background-color: rgba(74, 49, 49, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <video id="video-background" autoplay muted loop>
        <source src="home.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <div class="form-container">
            <h1>Forgot Your Password?</h1>
            <p>Please enter your email address below and we'll send you instructions on how to reset your password.</p>
            <form id="forgotPasswordForm" action="send-password-reset.php" method="POST">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="submit" name ="submit" class="button" value="Reset Password">
                <?php if (!empty($errorMessage)): ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
