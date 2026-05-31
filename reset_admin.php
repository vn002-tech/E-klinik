<?php
require('config.php');
require('app/models/PDODb.php');

try {
    $db = new PDODb(DB_TYPE, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
    
    // Check if the Admin user exists
    $db->where("username", "Admin");
    $user = $db->getOne("pengguna");
    
    if (!$user) {
        // If Admin user doesn't exist, create it
        $admin_data = array(
            "username" => "Admin",
            "nama" => "Paidi",
            "jabatan" => "Admin Data",
            "email" => "Paidi@gmail.com",
            "password" => password_hash('admin', PASSWORD_DEFAULT),
            "photo" => "assets/images/favicon.png",
            "user_role_id" => 1
        );
        $res = $db->insert("pengguna", $admin_data);
        if ($res) {
            echo "User 'Admin' didn't exist, but has been successfully created with password 'admin'!";
        } else {
            echo "Admin user did not exist, and failed to create it. Error: " . $db->getLastError();
        }
    } else {
        // Reset password for existing Admin user
        $new_password_hash = password_hash('admin', PASSWORD_DEFAULT);
        $db->where("username", "Admin");
        $res = $db->update("pengguna", array("password" => $new_password_hash));
        
        if ($res) {
            echo "Password for 'Admin' has been successfully reset to 'admin'!";
        } else {
            echo "Failed to reset password. Error: " . $db->getLastError();
        }
    }
} catch (Exception $e) {
    echo "Exception occurred: " . $e->getMessage();
}
