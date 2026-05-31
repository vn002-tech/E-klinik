<div id="topbar" class="custom-topbar fixed-top">
    <div class="topbar-left">
        <a class="brand-link" href="<?php print_link(HOME_PAGE) ?>">
            <img class="img-responsive" src="<?php print_link(SITE_LOGO); ?>" /> <span class="brand-text"><?php echo SITE_NAME ?></span>
        </a>
        <?php if(user_login_status() == true ){ ?>
        <button type="button" id="sidebarCollapse" class="clean-btn">
            <i class="fa fa-bars"></i>
        </button>
        <?php } ?>
    </div>
    
    <?php if(user_login_status() == true ){ 
        $is_verified = 'unverified';
        try {
            $db_ver = new PDODb(DB_TYPE, DB_HOST , DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
            $db_ver->where("user_id", USER_ID);
            $is_verified = $db_ver->getValue("user_settings", "is_verified") ?: 'unverified';
        } catch (Exception $e) {}
    ?>
    <div class="topbar-right">
        <div class="profile-dropdown dropdown">
            <a class="dropdown-toggle profile-link d-flex align-items-center" href="#" data-toggle="dropdown">
                <div class="position-relative d-inline-block" style="width: 30px; height: 30px;">
                    <?php if(!empty(USER_PHOTO)){ ?>
                        <img class="avatar-img" src="<?php print_link(set_img_src(USER_PHOTO,30,30)); ?>" />
                    <?php } else { ?>
                        <img class="avatar-img" src="<?php print_link(set_img_src('assets/images/avatar.png',30,30)); ?>" style="border-radius: 50%; width: 30px; height: 30px;" />
                    <?php } ?>
                    
                    <?php if ($is_verified == 'verified') { ?>
                        <span class="badge badge-success position-absolute" style="bottom: -2px; right: -2px; width: 10px; height: 10px; border-radius: 50%; padding: 0; border: 2px solid white; display: block;" title="Akun Terverifikasi"></span>
                    <?php } elseif ($is_verified == 'pending') { ?>
                        <span class="badge badge-warning position-absolute" style="bottom: -2px; right: -2px; width: 10px; height: 10px; border-radius: 50%; padding: 0; border: 2px solid white; display: block;" title="Verifikasi Tertunda"></span>
                    <?php } ?>
                </div>
                <span class="profile-name ml-2">Hi <?php echo ucwords(USER_NAME); ?> !</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right shadow-sm border-0 mt-2">
                <a class="dropdown-item py-2" href="<?php print_link('account') ?>"><i class="fa fa-user mr-2 text-muted"></i> My Account</a>
                <a class="dropdown-item py-2" href="<?php print_link('account/settings') ?>"><i class="fa fa-cog mr-2 text-muted"></i> Pengaturan Akun</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item py-2 text-danger" href="<?php print_link('index/logout?csrf_token=' . Csrf::$token) ?>"><i class="fa fa-sign-out mr-2"></i> Logout</a>
            </ul>
        </div>
    </div>
    <?php } ?>
</div>
        <?php 
        if(user_login_status() == true ){ 
        ?>
        <nav id="sidebar" class="navbar-light bg-white shadow-sm">
            <ul class="nav navbar-nav w-100 flex-column align-self-start">
                <li class="menu-profile text-center nav-item">
                    <a class="avatar" href="<?php print_link('account') ?>">
                        <?php 
                        if(!empty(USER_PHOTO)){
                        ?>
                        <img class="img-fluid" src="<?php print_link(set_img_src(USER_PHOTO,260,200)); ?>" />
                            <?php
                            }
                            else{
                            ?>
                            <img class="img-fluid user-photo" src="<?php print_link(set_img_src('assets/images/avatar.png',260,200)); ?>" style="border-radius: 50%; width: 60px; height: 60px; margin: 0 auto;" />
                            <?php
                            }
                            ?>
                        </a>
                        <h5 class="user-name">Hi 
                            <?php echo ucwords(USER_NAME); ?>
                            <small class="text-muted"><?php echo ACL::$user_role; ?> </small>
                        </h5>
                        <div class="dropdown menu-dropdown">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <a class="dropdown-item" href="<?php print_link('account') ?>"><i class="fa fa-user"></i> Profil Saya</a>
                                <a class="dropdown-item" href="<?php print_link('account/settings') ?>"><i class="fa fa-cog"></i> Pengaturan Akun</a>
                                <a class="dropdown-item" href="<?php print_link('index/logout?csrf_token=' . Csrf::$token) ?>"><i class="fa fa-sign-out"></i> Keluar</a>
                            </ul>
                        </div>
                    </li>
                </ul>
                
                <?php if (USER_ROLE_NAME == 'super_admin') { ?>
                    <h6 class="sidebar-heading px-3 mt-3 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">MONITORING UTAMA</h6>
                    <?php Html :: render_menu(Menu :: $navDashboard  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">PELAYANAN KLINIS</h6>
                    <?php Html :: render_menu(Menu :: $navKlinis  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">DATA MASTER MANAJEMEN</h6>
                    <?php Html :: render_menu(Menu :: $navMaster  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">SISTEM & OTENTIKASI HAK AKSES</h6>
                    <?php Html :: render_menu(Menu :: $navSistem  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>
                <?php } elseif (USER_ROLE_NAME == 'pasien') { 
                    $pasienDashboard = array(
                        array('path' => 'home', 'label' => 'Dashboard', 'icon' => '<i class="fa fa-home "></i>')
                    );
                    $pasienKlinis = array(
                        array('path' => 'rekam_medis', 'label' => 'Rekam Medis Saya', 'icon' => '<i class="fa fa-file-text-o "></i>')
                    );
                    $pasienSistem = array(
                        array('path' => 'account/settings', 'label' => 'Pengaturan Akun', 'icon' => '<i class="fa fa-cog "></i>')
                    );
                ?>
                    <h6 class="sidebar-heading px-3 mt-3 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Utama</h6>
                    <?php Html :: render_menu($pasienDashboard  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Klinis</h6>
                    <?php Html :: render_menu($pasienKlinis  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Sistem & Akun</h6>
                    <?php Html :: render_menu($pasienSistem  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>
                <?php } elseif (USER_ROLE_NAME == 'dokter') { 
                    $dokterDashboard = array(
                        array('path' => 'home', 'label' => 'Dashboard', 'icon' => '<i class="fa fa-home "></i>')
                    );
                    $dokterMaster = array(
                        array('path' => 'pasien', 'label' => 'Data Pasien Saya', 'icon' => '<i class="fa fa-user "></i>'),
                        array('path' => 'ruang', 'label' => 'Jadwal Ruang & Cek up', 'icon' => '<i class="fa fa-building "></i>')
                    );
                    $dokterKlinis = array(
                        array('path' => 'rekam_medis', 'label' => 'Input Rekam Medis', 'icon' => '<i class="fa fa-file-text-o "></i>')
                    );
                    $dokterSistem = array(
                        array('path' => 'account/settings', 'label' => 'Pengaturan Akun', 'icon' => '<i class="fa fa-cog "></i>')
                    );
                ?>
                    <h6 class="sidebar-heading px-3 mt-3 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Utama</h6>
                    <?php Html :: render_menu($dokterDashboard  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Klinis</h6>
                    <?php Html :: render_menu($dokterKlinis  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Master Data</h6>
                    <?php Html :: render_menu($dokterMaster  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>

                    <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Sistem & Akun</h6>
                    <?php Html :: render_menu($dokterSistem  , "nav navbar-nav w-100 flex-column align-self-start"  , "accordion"); ?>
                <?php } ?>
                
            </nav>
            <?php 
            } 
            ?>
