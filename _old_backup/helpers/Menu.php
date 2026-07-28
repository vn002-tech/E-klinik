<?php
/**
 * Menu Items
 * All Project Menu
 * @category  Menu List
 */

class Menu{
    public static $navDashboard = array(
        array('path' => 'home', 'label' => 'Dashboard Utama', 'icon' => '<i class="fa fa-desktop "></i>')
    );
    public static $navKlinis = array(
        array('path' => 'rekam_medis', 'label' => 'Rekam Medis', 'icon' => '<i class="fa fa-heartbeat "></i>')
    );
    public static $navMaster = array(
        array('path' => 'dokter', 'label' => 'Data Dokter', 'icon' => '<i class="fa fa-user-md "></i>'),
        array('path' => 'pasien', 'label' => 'Data Pasien', 'icon' => '<i class="fa fa-user "></i>'),
        array('path' => 'obat', 'label' => 'Data Obat', 'icon' => '<i class="fa fa-medkit "></i>'),
        array('path' => 'ruang', 'label' => 'Data Ruang', 'icon' => '<i class="fa fa-building "></i>')
    );
    public static $navSistem = array(
        array('path' => 'pengguna', 'label' => 'Manajemen Pengguna', 'icon' => '<i class="fa fa-users "></i>'),
        array(
            'path' => '#',
            'label' => 'Matriks Akses & Roles',
            'icon' => '<i class="fa fa-shield "></i>',
            'submenu' => array(
                array('path' => 'roles', 'label' => 'Roles List', 'icon' => '<i class="fa fa-gears "></i>'),
                array('path' => 'role_permissions', 'label' => 'Permissions Map', 'icon' => '<i class="fa fa-key "></i>')
            )
        ),
        array('path' => 'account/settings', 'label' => 'Pengaturan Akun', 'icon' => '<i class="fa fa-cog "></i>'),
        array('path' => 'settings', 'label' => 'Pengaturan Sistem', 'icon' => '<i class="fa fa-cogs "></i>')
    );
    
    // Kept for backward compatibility if needed elsewhere
    public static $navbarsideleft = array(
        array('path' => 'home', 'label' => 'Dashboard Utama', 'icon' => '<i class="fa fa-desktop "></i>'),
        array('path' => 'dokter', 'label' => 'Data Dokter', 'icon' => '<i class="fa fa-user-md "></i>'),
        array('path' => 'pasien', 'label' => 'Data Pasien', 'icon' => '<i class="fa fa-user "></i>'),
        array('path' => 'pengguna', 'label' => 'Manajemen Pengguna', 'icon' => '<i class="fa fa-users "></i>'),
        array('path' => 'obat', 'label' => 'Data Obat', 'icon' => '<i class="fa fa-medkit "></i>'),
        array('path' => 'ruang', 'label' => 'Data Ruang', 'icon' => '<i class="fa fa-building "></i>'),
        array('path' => 'rekam_medis', 'label' => 'Rekam Medis', 'icon' => '<i class="fa fa-heartbeat "></i>'),
        array('path' => 'roles', 'label' => 'Roles List', 'icon' => '<i class="fa fa-gears "></i>'),
        array('path' => 'role_permissions', 'label' => 'Permissions Map', 'icon' => '<i class="fa fa-key "></i>')
    );
}