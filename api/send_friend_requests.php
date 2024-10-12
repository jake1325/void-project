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
        'origin: https://hexagon.pw',
        'referer: https://hexagon.pw/users/5/profile',
        'sec-ch-ua: "Google Chrome";v="129", "Not=A?Brand";v="8", "Chromium";v="129"',
        'sec-ch-ua-mobile: ?1',
        'sec-ch-ua-platform: "Android"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Mobile Safari/537.36'
    ];

    // Loop through IDs from start_id to end_id
    for ($recipient_id = $start_id; $recipient_id <= $end_id; $recipient_id++) {
        // Payload for the POST request
        $data = json_encode([
            'recipientid' => $recipient_id,
            'type' => 'friend'
        ]);

        // Initialize cURL session
        $ch = curl_init($url);

        // cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));

        // Execute the request and get the response
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check the response status
        if ($http_code == 200) {
            echo "Friend request sent to ID {$recipient_id}<br>";
        } elseif ($http_code == 401) {
            echo "Authentication failed for ID {$recipient_id}. Status code: {$http_code}<br>";
            break; // Stop the loop on authentication failure
        } else {
            echo "Failed to send friend request to ID {$recipient_id}. Status code: {$http_code}<br>";
        }

        // Close the cURL session
        curl_close($ch);
    }
} else {
    echo "Invalid request method.";
}
?>
