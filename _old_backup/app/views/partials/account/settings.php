<?php
$user = $this->view_data['user'];
$settings = $this->view_data['settings'];
$page_element_id = "settings-page-" . random_str();
$csrf_token = Csrf::$token;
?>
<section class="page" id="<?php echo $page_element_id; ?>">
    <div class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="record-title font-weight-bold text-primary"><i class="fa fa-cog mr-2"></i>Pengaturan Akun</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php $this :: display_page_errors(); ?>
                    
                    <!-- Profile Card -->
                    <div class="card bg-primary text-white mb-4 shadow-sm">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-container position-relative mr-4">
                                    <?php 
                                    if(!empty($user['photo'])){
                                        Html::page_img($user['photo'], 90, 90, 1, null, "rounded-circle border border-white"); 
                                    } else {
                                        Html::page_img("assets/images/avatar.png", 90, 90, 1, null, "rounded-circle border border-white");
                                    }
                                    ?>
                                </div>
                                <div>
                                    <h3 class="mb-1 font-weight-bold"><?php echo ucwords($user['nama']); ?></h3>
                                    <p class="mb-2 opacity-75">@<?php echo $user['username']; ?> | <?php echo $user['email']; ?></p>
                                    
                                    <!-- Verification Status Badge -->
                                    <?php if ($settings['is_verified'] == 'verified') { ?>
                                        <span class="badge badge-success px-3 py-2"><i class="fa fa-check-circle mr-1"></i>Akun Terverifikasi</span>
                                    <?php } elseif ($settings['is_verified'] == 'pending') { ?>
                                        <span class="badge badge-warning text-dark px-3 py-2"><i class="fa fa-clock-o mr-1"></i>Verifikasi Tertunda</span>
                                    <?php } else { ?>
                                        <span class="badge badge-secondary px-3 py-2"><i class="fa fa-exclamation-circle mr-1"></i>Belum Terverifikasi</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body p-2">
                                    <ul class="nav nav-pills flex-column text-left">
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="#Notifikasi" class="nav-link active py-3">
                                                <i class="fa fa-bell mr-2 text-primary"></i> Notifikasi
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="#Keamanan" class="nav-link py-3">
                                                <i class="fa fa-shield mr-2 text-primary"></i> Keamanan & Verifikasi
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card shadow-sm border-0 mb-4 p-4">
                                <form method="post" action="<?php print_link("account/settings?csrf_token=$csrf_token"); ?>" enctype="multipart/form-data">
                                    <div class="tab-content">
                                        
                                        <!-- Tab 1: Notifikasi -->
                                        <div class="tab-pane show active fade" id="Notifikasi" role="tabpanel">
                                            <h5 class="font-weight-bold text-dark border-bottom pb-3 mb-4">Pengaturan Notifikasi</h5>
                                            
                                            <div class="form-group mb-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="font-weight-bold mb-1">Notifikasi Jadwal Pemeriksaan via WhatsApp</h6>
                                                        <span class="text-muted small">Kirim pengingat pemeriksaan klinis otomatis ke nomor WhatsApp Anda.</span>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="notif_whatsapp" value="1" class="custom-control-input" id="switch-wa" <?php echo ($settings['notif_whatsapp'] == 1 ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label" for="switch-wa"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="font-weight-bold mb-1">Notifikasi Hasil Rekam Medis via Email</h6>
                                                        <span class="text-muted small">Dapatkan salinan surat rujukan dan hasil rekam medis langsung di inbox email Anda.</span>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="notif_email" value="1" class="custom-control-input" id="switch-email" <?php echo ($settings['notif_email'] == 1 ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label" for="switch-email"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tab 2: Keamanan & Verifikasi -->
                                        <div class="tab-pane fade" id="Keamanan" role="tabpanel">
                                            
                                            <!-- Ubah Password -->
                                            <h5 class="font-weight-bold text-dark border-bottom pb-3 mb-4">Ubah Password</h5>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="control-label font-weight-bold" for="current_password">Password Saat Ini</label>
                                                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password lama">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="control-label font-weight-bold" for="new_password">Password Baru</label>
                                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Minimal 6 karakter">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="control-label font-weight-bold" for="confirm_password">Konfirmasi Password Baru</label>
                                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password baru">
                                                </div>
                                            </div>

                                            <!-- 2FA module -->
                                            <h5 class="font-weight-bold text-dark border-bottom pb-3 mt-4 mb-4">Keamanan Dua Langkah (2FA)</h5>
                                            <div class="form-group mb-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="font-weight-bold mb-1">Aktifkan Autentikasi Dua Faktor (2FA)</h6>
                                                        <span class="text-muted small">Amankan akun Anda dengan meminta kode verifikasi saat masuk.</span>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="two_factor_enabled" value="1" class="custom-control-input" id="switch-2fa" <?php echo ($settings['two_factor_enabled'] == 1 ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label" for="switch-2fa"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Account Verification Section -->
                                            <h5 class="font-weight-bold text-dark border-bottom pb-3 mt-4 mb-4">Verifikasi Akun Resmi</h5>
                                            <div class="row align-items-center">
                                                <div class="col-md-12">
                                                    <?php if ($settings['is_verified'] == 'verified') { ?>
                                                        <div class="alert alert-success d-flex align-items-center">
                                                            <i class="fa fa-check-circle fa-2x mr-3"></i>
                                                            <div>
                                                                <h6 class="font-weight-bold mb-0 text-dark">Akun Anda Sudah Terverifikasi</h6>
                                                                <span class="small">Identitas Anda telah dikonfirmasi oleh sistem administrator.</span>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <?php if ($settings['is_verified'] == 'pending') { ?>
                                                            <div class="alert alert-warning text-dark d-flex align-items-center mb-3">
                                                                <i class="fa fa-info-circle fa-2x mr-3"></i>
                                                                <div>
                                                                    <h6 class="font-weight-bold mb-0">Dokumen Anda Sedang Ditinjau</h6>
                                                                    <span class="small">Administrator sedang memeriksa dokumen identitas Anda.</span>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="alert alert-light border d-flex align-items-center mb-3">
                                                                <i class="fa fa-info-circle fa-2x text-muted mr-3"></i>
                                                                <div>
                                                                    <h6 class="font-weight-bold mb-0 text-muted">Akun Belum Diverifikasi</h6>
                                                                    <span class="small text-muted">Unggah KTP/Passport Anda untuk memverifikasi akun medis Anda.</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        
                                                        <div class="form-group mt-3">
                                                            <label class="font-weight-bold text-dark">Unggah Dokumen Identitas (KTP / Passport / SIM)</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="verification_document" class="custom-file-input" id="id-document" accept=".jpg,.png,.jpeg,.pdf">
                                                                <label class="custom-file-label" for="id-document">Pilih file KTP/Passport...</label>
                                                            </div>
                                                            <small class="form-text text-muted mt-2">Mendukung format JPG, PNG, atau PDF. Maksimal 3MB.</small>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit buttons -->
                                    <div class="border-top pt-3 mt-4 text-right">
                                        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold">
                                            Simpan Pengaturan <i class="fa fa-save ml-1"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Styles and Scripts to enrich the UI interaction -->
<style>
    .opacity-75 { opacity: 0.75; }
    .custom-switch .custom-control-label::before {
        height: 1.5rem;
        width: 2.75rem;
        border-radius: 1rem;
    }
    .custom-switch .custom-control-label::after {
        width: calc(1.5rem - 4px);
        height: calc(1.5rem - 4px);
        border-radius: 1rem;
        background-color: #adb5bd;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, transform 0.15s ease-in-out;
    }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(1.25rem);
        background-color: #ffffff;
    }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
<script>
    // Update label when file is chosen
    document.getElementById('id-document')?.addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || "Pilih file KTP/Passport...";
        var label = e.target.nextElementSibling;
        label.innerText = fileName;
    });
</script>
