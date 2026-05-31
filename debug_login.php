<?php
header('Content-Type: text/plain');
require('config.php');
require('libs/PDODb.php');

try {
    $db = new PDODb(DB_TYPE, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
    
    // Check if the Admin user exists
    $db->where("username", "Admin");
    $user = $db->getOne("pengguna");
    
    if (!$user) {
        echo "User 'Admin' not found in database!\n";
        
        // Let's get all users
        $all_users = $db->get("pengguna");
        echo "Total users in database: " . count($all_users) . "\n";
        if ($all_users) {
            foreach ($all_users as $u) {
                echo "Username: " . $u['username'] . ", Email: " . $u['email'] . "\n";
            }
        }
    } else {
        echo "User 'Admin' found!\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Hash in DB: " . $user['password'] . "\n";
        
        $test_pw = 'admin';
        $verify = password_verify($test_pw, $user['password']);
        echo "Testing password_verify('$test_pw', hash): " . ($verify ? "SUCCESS (true)" : "FAILED (false)") . "\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
