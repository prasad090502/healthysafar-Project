<?php
// Password Hash Generator for Admin User Creation
// Replace 'your_password_here' with your desired password

$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Generated Hash: " . $hash . "\n\n";

// Example usage with different passwords
echo "Example hashes for common passwords:\n\n";

$examples = ['admin123', 'password', 'admin', '123456'];

foreach ($examples as $example) {
    $exampleHash = password_hash($example, PASSWORD_DEFAULT);
    echo "Password: '$example' -> Hash: $exampleHash\n";
}
?>
