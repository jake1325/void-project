<?php
if (isset($_GET['request'])) {
    $request = $_GET['request'];
    $parts = explode('-', $request);
    
    if (count($parts) > 1) {
        $prefix = $parts[0];
        $length = 7;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        $randomCode = $prefix . '-' . $randomString;
        
        echo json_encode([
            'status' => 'success',
            'request' => $request,
            'generated_code' => $randomCode
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request format. It should contain a prefix followed by a hyphen.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No request parameter provided.'
    ]);
}
?>
