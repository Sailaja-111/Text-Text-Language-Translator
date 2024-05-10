<?php
if(isset($_POST['text']) && isset($_POST['target_language'])) {
    $input_text = $_POST['text'];
    $target_language = $_POST['target_language'];

    // Your translation API endpoint URL
    $url = 'http://localhost:5000/translate';
    
    // Data to be sent in the POST request
    $data = array(
        'inputText' => $input_text,
        'targetLanguage' => $target_language
    );

    // Constructing HTTP POST request
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    
    // Creating a stream context for the HTTP request
    $context  = stream_context_create($options);
    
    // Making the HTTP POST request
    $result = file_get_contents($url, false, $context);
    
    // Checking if the request was successful
    if ($result === FALSE) {
        echo "Error: Unable to connect to the translation service.";
    } else {
        // Parsing the JSON response
        $json = json_decode($result, true);
        
        // Checking if the translation was successful
        if (isset($json['translatedText'])) {
            echo $json['translatedText'];
        } else {
            echo "Error: Translation failed.";
        }
    }
} else {
    echo "Error: Input text or target language not provided.";
}
?>