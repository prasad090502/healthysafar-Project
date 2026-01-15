<?php
$password = '05F@b2005';
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO customers (name, username, email, contact, password_hash, role) VALUES ('Admin User', 'admin', 'admin@hsafar.com', '1234567890', '$hash', 'admin');";

echo "SQL to create admin user:\n";
echo $sql . "\n\n";
echo "Password: $password\n";
echo "Hash: $hash\n";
?>
