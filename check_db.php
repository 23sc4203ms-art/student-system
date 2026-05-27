<?php
$host = 'localhost';
$db = 'jmmlaraveldb';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, username, email, Role, is_active FROM user_accounts");
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "User Accounts in Database:\n";
    echo str_repeat("=", 60) . "\n";
    foreach ($accounts as $acc) {
        echo "ID: {$acc['id']}, Username: {$acc['username']}, Email: {$acc['email']}, Role: {$acc['Role']}, Active: {$acc['is_active']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
