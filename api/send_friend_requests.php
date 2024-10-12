<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the .ROBLOSECURITY value and IDs from the form input
    $roblosecurity = $_POST['roblosecurity'];
    $start_id = intval($_POST['start_id']); // Convert to integer
    $end_id = intval($_POST['end_id']); // Convert to integer

    // URL for the API endpoint
    $url = "https://hexagon.pw/api/account/friend";

    // Headers for the request
    $headers = [
        'cookie: .ROBLOSECURITY=' . $roblosecurity,
        'Content-Type: application/json'
    ];

    // Loop through IDs from start_id to end_id
    for ($recipient_id = $start_id; $recipient_id <= $end_id; $recipient_id++) {
        // Payload for the POST request
        $data = json_encode([
            'recipientid' => $recipient_id,
            'type' => 'friend'
        ]);

        // Create a stream context for the request
        $options = [
            'http' => [
                'header'  => implode("\r\n", $headers),
                'method'  => 'POST',
                'content' => $data,
            ]
        ];
        $context  = stream_context_create($options);

        // Send the request
        $response = file_get_contents($url, false, $context);
        $http_code = ($response === FALSE) ? 500 : 200; // Basic error handling

        // Check the response status
        if ($http_code == 200) {
            echo "Friend request sent to ID {$recipient_id}<br>";
        } elseif ($http_code == 401) {
            echo "Authentication failed for ID {$recipient_id}. Status code: {$http_code}<br>";
            break; // Stop the loop on authentication failure
        } else {
            echo "Failed to send friend request to ID {$recipient_id}. Status code: {$http_code}<br>";
        }
    }
} else {
    echo "Invalid request method.";
}
?>
