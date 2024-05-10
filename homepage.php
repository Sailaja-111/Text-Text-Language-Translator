<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Translation App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('h1.jpg');
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat; 
            height: 100vh; 
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        h1 {
            color: black;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .button-container {
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to the Text Translation App</h1>
    <div class="button-container">
        <a href="login.php" class="button" id="loginButton">Login</a>
        <a href="register.php" class="button" id="registerButton">Register</a>
    </div>
</div>

</body>
</html>
