<?php
header('Content-Type: text/plain');
require('config.php');
require('app/models/PDODb.php');

try {
    $db = new PDODb(DB_TYPE, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
    
    // Exact logic from IndexController.php
    $username = 'admin'; // Testing 'admin'
    $username_sanitized = filter_var($username, FILTER_SANITIZE_STRING);
    
    $db->where("username", $username_sanitized)->orWhere("email", $username_sanitized);
    $user = $db->getOne("pengguna");
    
    if (empty($user)) {
        echo "User matching '$username' NOT found via exact IndexController query logic!\n";
        echo "Sanitized username: '$username_sanitized'\n";
    } else {
        echo "User matching '$username' FOUND!\n";
        echo "Username in DB: " . $user['username'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Password Hash in DB: " . $user['password'] . "\n";
        
        $password_text = 'admin'; // testing password 'admin'
        if (password_verify($password_text, $user['password'])) {
            echo "Password verify SUCCESS!\n";
        } else {
            echo "Password verify FAILED!\n";
        }
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
