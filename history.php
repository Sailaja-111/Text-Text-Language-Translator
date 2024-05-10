<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit; // Always exit after header redirect
}

// Get email from session
if(isset($_SESSION["user"]) && is_array($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
} else {
    // Handle the case when the session variable is not set or not in the expected format
    // You can redirect the user to the login page or display an error message
}

// Database connection
$servername = "localhost";
$db_username = "root"; // Renamed to avoid conflict
$password = "";
$dbname = "login_register";

// Create connection
$conn = new mysqli($servername, $db_username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch translation history data for the logged-in user
$sql = "SELECT * FROM translation_history WHERE email='$email' ORDER BY date DESC, time DESC";
$result = $conn->query($sql);

// Read translation history from file
$historyFile = "translation_history.txt";
$historyData = file($historyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Translation App - History</title>
    <style>
        /* Your CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: linear-gradient(to right, #87ceeb, #ff69b4); /* Set colorful background gradient */
        }

        nav {
            background: linear-gradient(to right, #6495ed, #ff1493); /* Set colorful navbar gradient */
            padding: 10px;
            color: #fff;
            display: flex;
            justify-content: space-between;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }

        table {
            width: 100%;
            /* border-collapse: collapse; */
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: linear-gradient(to right, #6495ed, #ff69b4); /* Set colorful table header gradient */
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<nav>
    <div>
        <a href="home.html">Home</a>
        <a href="history.php">History</a>
    </div>
    <div>
        <a href="homepage.php">Logout</a>
    </div>
</nav>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Input Text</th>
            <th>Translated Text</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        <!-- Display data from database -->
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["input_text"] . "</td>";
                echo "<td>" . $row["translated_text"] . "</td>";
                echo "<td>" . $row["time"] . "</td>";
                echo "</tr>";
            }
        } else {
            // echo "<tr><td colspan='4'>No translation history found.</td></tr>";
        }
        ?>
        
        <!-- Display translation history from file -->
        <?php
        foreach ($historyData as $historyEntry) {
            $entryDetails = explode("|", $historyEntry);
            echo "<tr>";
            echo "<td>" . $entryDetails[0] . "</td>"; // Date
            echo "<td>" . $entryDetails[1] . "</td>"; // Input Text
            echo "<td>" . $entryDetails[2] . "</td>"; // Translated Text
            echo "<td>" . $entryDetails[3] . "</td>"; // Time
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close the database connection after all queries are executed
$conn->close();
?>
