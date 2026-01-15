<?php

// Test admin route access protection
echo "Testing Admin Route Access Protection\n\n";

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app' . DIRECTORY_SEPARATOR);

echo "Checking if AdminAuthFilter is properly configured...\n";

// Check if filter file exists
$filterFile = APPPATH . 'Filters/AdminAuthFilter.php';
if (file_exists($filterFile)) {
    echo "✅ AdminAuthFilter.php exists\n";
} else {
    echo "❌ AdminAuthFilter.php missing\n";
}

// Check if filter is registered in Filters.php
$filtersConfig = APPPATH . 'Config/Filters.php';
if (file_exists($filtersConfig)) {
    $content = file_get_contents($filtersConfig);
    if (strpos($content, "'adminAuth' =>") !== false) {
        echo "✅ adminAuth filter is registered\n";
    } else {
        echo "❌ adminAuth filter not found in Filters.php\n";
    }
}

// Check routes
$routesFile = APPPATH . 'Config/Routes.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    if (strpos($content, "'filter' => 'adminAuth'") !== false) {
        echo "✅ adminAuth filter applied to admin routes\n";
    } else {
        echo "❌ adminAuth filter not applied to admin routes\n";
    }
}

echo "\nTest completed.\n";
?>
