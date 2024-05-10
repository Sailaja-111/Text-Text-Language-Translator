<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: index.php");
    exit; // Always exit after header redirect
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Text Translation App</title>
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
            color: #0e0707;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
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
    </style>
</head>
<body>
    <video id="video-background" autoplay muted loop>
        <source src="home.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <div class="form-container">
            <?php
            if(isset($_POST["submit"])){
                $email=$_POST["email"];
                $password=$_POST["password"];
                require_once "database.php";
                $sql="SELECT * FROM users WHERE email='$email'";
                $result=mysqli_query($conn,$sql);
                $user=mysqli_fetch_array($result,MYSQLI_ASSOC);
                if($user){
                    if(password_verify($password,$user["password"])){
                        // Store user information in session
                        $_SESSION["user"]["email"] = $email;
                        $_SESSION["user"]["id"] = $user["id"]; // You can store other user information as needed
                        header("Location: index.php");
                        exit; // Always exit after header redirect
                    }else{
                        echo "<div style='background-color: rgba(255, 0, 0, 0.8); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>Password does not match</div>";
                    }
                }
                else{
                    echo "<div style='background-color: rgba(255, 0, 0, 0.8); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>Email does not match</div>";
                }
            }
            ?>

            <h1>Login to Text Translation App</h1>
            <form id="loginForm" action="login.php" method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Enter Email:" required><br>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Enter Password :" required><br>
                </div>
                <div class="form-btn">
                    <input type="submit" name="submit" class="button" value="Login">
                </div>
            </form>
            <a href="send-password-reset.php" class="button">Forgot Password</a><br>
            <a href="register.php" class="registerButton">New user? </a>
        </div>
    </div>
</body>
</html>
