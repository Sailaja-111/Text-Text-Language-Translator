<?php
session_start();

$errorMessage = "";

if(isset($_POST["submit"])) {
    $enteredOTP = $_POST["otp"];
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    
    if(isset($_SESSION["otp"]) && $_SESSION["otp"] == $enteredOTP) {
        if($newPassword === $confirmPassword) {
            // Update password in the database (Replace this with your database update query)
            // For example: $updateQuery = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
            // Execute the query and handle any errors
            
            header("Location: index.php");
            exit;
        } else {
            $errorMessage = "Passwords do not match. Please try again.";
        }
    } else {
        $errorMessage = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Text Translation App</title>
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

        input[type="text"], input[type="password"], input[type="email"] {
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
            <h1>Reset Your Password</h1>
            <form id="resetPasswordForm" action="reset.php" method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" required><br>
                <input type="password" name="password" placeholder="New Password" required><br>
                <input type="password" name="confirmPassword" placeholder="Confirm Password" required><br>
                <input type="submit" name="submit" class="button" value="Reset Password">
                <?php if (!empty($errorMessage)): ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
