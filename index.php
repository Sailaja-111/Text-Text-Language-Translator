<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

function detectLanguage($text, $apiKey)
{
    $url = 'https://ws.detectlanguage.com/0.2/detect';

    $postData = array(
        'q' => $text,
        'key' => $apiKey
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);

    if (isset($json['data']['detections'][0]['language'])) {
        return $json['data']['detections'][0]['language'];
    } else {
        return 'Unknown';
    }
}

// Check if the detect language button is clicked
if (isset($_POST['detectLanguage'])) {
    $inputText = $_POST['inputText'];
    $apiKey = '4f38376c7134c13ea11f4ceac783b3fe'; // Your detectlanguage.com API key
    $detectedLanguage = detectLanguage($inputText, $apiKey);
    echo json_encode(array('language' => $detectedLanguage));
    exit; // Stop further execution
}

// Check if the translate button is clicked
if (isset($_POST['translate'])) {
    $inputText = $_POST['inputText'];
    $targetLanguage = $_POST['targetLanguage'];
    $translatedText = translateText($inputText, $targetLanguage);
    echo json_encode(array('translatedText' => $translatedText));
    $text = date("Y-m-d") . "|" . $inputText . "|" . $translatedText . "|" . date("H:i:s") . PHP_EOL;

// File path
$filename = 'translation_history.txt';

// Append text to file
file_put_contents($filename, $text, FILE_APPEND | LOCK_EX);

    // $historyFile = "translation_history.txt";
    // $historyEntry = date("Y-m-d") . "|" . $inputText . "|" . $translatedText . "|" . date("H:i:s") . PHP_EOL;
    // file_put_contents($historyFile, $historyEntry, FILE_APPEND);
    exit; // Stop further execution
}

function translateText($inputText, $targetLanguage)
{
    // Execute the translation API request using translate_api.php
    $command = escapeshellcmd('python translate_api.py');
    $output = shell_exec($command . ' ' . escapeshellarg($inputText) . ' ' . escapeshellarg($targetLanguage));
    return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Translation App</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; 
            background: linear-gradient(to right, #87ceeb, #ff69b4); 
        }

        #video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1; 
        }

        nav {
            background: linear-gradient(to right, #6495ed, #ff1493); 
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

        .container {
            display: flex;
            flex-direction: row;
            width: 100vw; 
            height: 100vh; 
        }

        .column {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; 
            justify-content: center; 
        }

        .form-container {
            background: linear-gradient(to right, #87cefa, #ff7f50); 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
            width: 90%; /* Set width to 90% of parent */
            max-width: 600px; /* Limit max-width */
        }

        h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 30px;
            text-align: center; /* Center the heading */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); 
            display: inline-block; /* Make the heading inline-block to put detected language beside it */
        }

        .language-button {
            padding: 10px 20px;
            background-color: #6495ed;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .language-button:hover {
            background-color: #ff69b4;
        }

        textarea {
            width: 100%;
            height: 300px; /* Set height to accommodate 20 lines */
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ff69b4; 
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px; 
            resize: vertical; 
            background: linear-gradient(to right, #00bfff, #ffb6c1); 
        }

        textarea:focus {
            outline: none;
            border-color: #ff1493; 
        }

        select {
            width: calc(100% - 20px); /* Adjust width to fit the container */
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ff69b4; /* Set border color */
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px; /* Set font size */
            background: linear-gradient(to right, #00bfff, #ffb6c1); /* Set colorful select gradient */
        }

        select:focus {
            outline: none;
            border-color: #ff1493; 
        }

        .button {
            width: 100%; 
            padding: 15px 0; 
            background: linear-gradient(to right, #6495ed, #ff69b4); /* Set colorful button gradient */
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 16px; /* Set font size */
        }

        .button:hover {
            background: linear-gradient(to right, #ff69b4, #6495ed);
        }
    </style>
</head>
<body>
<nav>
    <div>
        <a href="index.php">Home</a>
        <a href="history.php">History</a>
    </div>
    <div>
        <a href="logout.php">Logout</a>
    </div>
</nav>
<!-- Your existing HTML content -->
<div class="container">
    <div class="column">
        <div class="form-container">
            <h1>Input the Text</h1>
            <span id="detectedLanguage" class="language-button"></span><br>
            <textarea id="inputText" name="inputText" placeholder="Enter text to translate" required></textarea>
            <!-- Add the language detection button -->
            <button id="detectLanguageButton" class="button">Detect Language</button>
            <input type="hidden" id="detectedLanguageInput" name="detectedLanguage">
        </div>
    </div>
    <div class="column">
        <div class="form-container">
            <h1>Translated Text</h1>
            <select name="translationOptions">
                
    <option value="es">Spanish</option>
    <option value="fr">French</option>
    <option value="de">German</option>
    <option value="it">Italian</option>
    <option value="ja">Japanese</option>
    <option value="en">English</option>
    <select name="translationOptions">
</select>

            </select><br>
            <textarea name="translatedText" id="translatedText" placeholder="Translated text will appear here" readonly></textarea><br>
            <button id="translateButton" class="button">Translate</button>
        </div>
    </div>
</div>

<script>
document.getElementById('detectLanguageButton').addEventListener('click', function() {
    var inputText = document.getElementById('inputText').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); // Send the request to the same URL
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                var detectedLanguage = response.language;
                // Update the detected language button text
                document.getElementById('detectedLanguage').textContent = detectedLanguage;
                // Store the detected language in a hidden input field
                document.getElementById('detectedLanguageInput').value = detectedLanguage;
            } else {
                alert('Error: Unable to detect language');
            }
        }
    };
    xhr.send('inputText=' + encodeURIComponent(inputText) + '&detectLanguage=1');
});

document.getElementById('translateButton').addEventListener('click', function() {
    var inputText = document.getElementById('inputText').value;
    var targetLanguage = document.querySelector('select[name="translationOptions"]').value;
    var detectedLanguage = document.getElementById('detectedLanguageInput').value;

    // Send the translation request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://127.0.0.1:5000/translate', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                document.getElementById('translatedText').textContent = response.translatedText;

                // Send the translated text to history.php for storage
                var translatedText = response.translatedText;
                var historyXhr = new XMLHttpRequest();
                historyXhr.open('POST', 'history.php', true);
                historyXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                historyXhr.send('translatedText=' + encodeURIComponent(translatedText));
            } else {
                alert('Error: Unable to translate text');
            }
        }
    };
    xhr.send('text=' + encodeURIComponent(inputText) + '&target_language=' + encodeURIComponent(targetLanguage) + '&detected_language=' + encodeURIComponent(detectedLanguage));
});



</script>

</body>
</html>