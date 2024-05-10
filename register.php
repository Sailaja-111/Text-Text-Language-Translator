<?php
  session_start();
  if(isset($_SESSION["user"])){
    header("Location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Text Translation App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1000;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1; /* Ensure form appears above the video */
        }

        .form-container {
            background-color: rgba(168, 82, 82, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
        }

        h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 30px;
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
    <!-- Your browser does not support the video tag. -->
</video>

<div class="container">
    <div class="form-container">
        <?php
        // $_POST
        // print_r($_POST);
        if(isset($_POST["submit"])){
            $username=$_POST["username"];
            $email=$_POST["email"];
            $password=$_POST["password"];
            $passwordRepeat=$_POST["confirmPassword"];
            $passwordHash=password_hash($password,PASSWORD_DEFAULT);
            $errors=array();
            if(empty($username) OR empty($email) OR empty($password) OR empty($passwordRepeat)){
                array_push($errors,"All fields are required");
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                array_push($errors,"Email is not valid");
            }
            if(strlen($password)<8){
                array_push($errors,"Password must be atleast 8 characters long");
            }
            if($password!=$passwordRepeat){
                array_push($errors,"Password does not match");
            }
            require_once "database.php";
            $sql="SELECT * FROM users WHERE email='$email'";
            $result=mysqli_query($conn,$sql);
            $rowCount=mysqli_num_rows($result);
            if($rowCount>0){
                array_push($errors,"Email already exists!");
            }
            if(count($errors)>0){
                foreach($errors as $error){
                    echo "<div style='background-color: rgba(255, 0, 0, 0.8); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>$error</div>";
                }
            }else
            {
                $sql="INSERT INTO users(username,email,password) VALUES (?,?,?)";
                $stmt=mysqli_stmt_init($conn);
                $prepareStmt=  mysqli_stmt_prepare($stmt,$sql);
                if($prepareStmt){
                    mysqli_stmt_bind_param($stmt,"sss",$username,$email,$passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div style='background-color: rgba(0, 128, 0, 0.8); color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>You are registered successfully</div>";
                }
                else{
                    die("Something went wrong");
                }
            }
        }
        ?>
        <h1>Register for Text Translation App</h1>
        <form id="registerForm" action="register.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" ><br>
            </div>
            <div class="form-group">
            <input type="email" name="email" placeholder="Email" ><br>
            </div>
            <div class="form-group">
            <input type="password" name="password" placeholder="Password" ><br>
            </div>
            <div class="form-group">
            <input type="password" name="confirmPassword" placeholder="Confirm Password" ><br>
            </div>
            <div class="form-group">
            <input type="submit" class="button" value="Register" name="submit"><br>
            </div>
            <!-- required -->
            <a href="login.php" class="loginButton">Already a user?</a>
        </form>
    </div>
</div>

</body>
</html>
