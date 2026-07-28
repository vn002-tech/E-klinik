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
            "email" => "vn002@gmail.com",
            "password" => password_hash('admin', PASSWORD_DEFAULT),
            "photo" => "assets/images/favicon.png",
            "user_role_id" => 1
        );
        $res = $db->insert("pengguna", $admin_data);
        if ($res) {
            echo "User 'Admin' has been successfully created with email 'vn002@gmail.com' and password 'admin'!";
        } else {
            echo "Failed to create Admin user. Error: " . $db->getLastError();
        }
    } else {
        // Reset password and email for existing Admin user
        $new_data = array(
            "password" => password_hash('admin', PASSWORD_DEFAULT),
            "email" => "vn002@gmail.com"
        );
        $db->where("username", "Admin");
        $res = $db->update("pengguna", $new_data);
        
        if ($res) {
            echo "Admin credentials have been successfully reset (Email: vn002@gmail.com, Password: admin)!";
        } else {
            echo "Failed to update Admin credentials. Error: " . $db->getLastError();
        }
    }
} catch (Exception $e) {
    echo "Exception occurred: " . $e->getMessage();
}
