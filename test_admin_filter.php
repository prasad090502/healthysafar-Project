<?php

// Simple test to check if admin filter is working
// This script simulates accessing admin routes without authentication

echo "Testing Admin Route Protection...\n\n";

// Simulate accessing admin dashboard without login
$url = 'http://localhost/hsafar/public/admin/dashboard';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: Test Script',
        'timeout' => 10,
    ]
]);

echo "Attempting to access: $url\n";
$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Failed to access the URL\n";
} else {
    // Check if we got redirected to login
    $headers = $http_response_header ?? [];
    $location = '';
    foreach ($headers as $header) {
        if (stripos($header, 'Location:') === 0) {
            $location = trim(substr($header, 9));
            break;
        }
    }

    if (strpos($location, 'admin/login') !== false) {
        echo "✅ SUCCESS: Redirected to login page as expected\n";
        echo "Redirect location: $location\n";
    } else {
        echo "❌ FAILED: Not redirected to login\n";
        echo "Response contains: " . substr($response, 0, 200) . "...\n";
    }
}

echo "\nTest completed.\n";
?>
