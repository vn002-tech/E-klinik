<?php
$comp_model = new SharedController;
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;

$backups = $this->view_data['backups'] ?? array();
$thresholds = $this->view_data['thresholds'] ?? array();
$logs = $this->view_data['logs'] ?? array();

$min_stock = $thresholds['min_safety_stock_obat'] ?? 20;
$max_queue = $thresholds['max_queue_per_doctor'] ?? 8;
?>

<style>
.settings-tabs .nav-link {
    font-weight: 600;
    color: #495057;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 12px 20px;
    transition: all 0.2s;
}

.settings-tabs .nav-link.active {
    color: #007bff !important;
    background-color: transparent !important;
    border-bottom-color: #007bff !important;
}

.settings-tabs .nav-link:hover {
    color: #007bff;
    border-bottom-color: rgba(0, 123, 255, 0.3);
}

.mobile-backup-card {
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg-card);
    padding: 15px;
    margin-bottom: 12px;
}

.log-timeline-item {
    border-left: 2px solid #e9ecef;
    padding-left: 20px;
    position: relative;
}

.log-timeline-item::before {
    content: '';
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #007bff;
    position: absolute;
    left: -7px;
    top: 5px;
    border: 2px solid #fff;
}

.log-timestamp {
    font-size: 11px;
    font-weight: 600;
}
</style>

<div class="border-top pt-3">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12 comp-grid">
                <h4 class="font-weight-bold text-primary text-uppercase tracking-wider">
                    <i class="fa fa-cogs mr-2"></i>Pengaturan Sistem
                </h4>
                <p class="text-muted">Konfigurasi ambang batas EWS, pemeliharaan database, dan penelusuran audit log aktivitas sistem.</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card border-0 shadow-sm rounded-lg mb-4" style="background: var(--bg-card); border: 1px solid var(--border) !important;">
            <div class="card-header bg-white p-0 border-bottom">
                <ul class="nav nav-tabs settings-tabs border-0" id="settingsTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="backup-tab" data-toggle="tab" href="#backup" role="tab" aria-controls="backup" aria-selected="true">
                            <i class="fa fa-database mr-2"></i>Backup & Pemulihan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ews-tab" data-toggle="tab" href="#ews" role="tab" aria-controls="ews" aria-selected="false">
                            <i class="fa fa-sliders mr-2"></i>Ambang Batas Sistem (EWS)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false">
                            <i class="fa fa-history mr-2"></i>Log Aktivitas Audit
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content" id="settingsTabContent">
                    
                    <!-- TAB 1: BACKUP & DATABASE MAINTENANCE -->
                    <div class="tab-pane fade show active" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                            <div>
                                <h5 class="font-weight-bold text-dark m-0">Cadangan Database Manual</h5>
                                <p class="text-muted m-0"><small>Buat cadangan database relasional klinis lengkap (.ZIP) dan simpan dengan aman di server.</small></p>
                            </div>
                            <button class="btn btn-primary font-weight-bold py-2 px-3 shadow-sm mt-2 mt-sm-0" id="btn-run-backup">
                                <i class="fa fa-cloud-download mr-1"></i> Buat Cadangan Database Baru
                            </button>
                        </div>
                        
                        <!-- Desktop View Table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Backup</th>
                                        <th>Nama Berkas</th>
                                        <th>Ukuran File</th>
                                        <th class="text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($backups)) { 
                                        $i = 1;
                                        foreach ($backups as $b) { ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $b['created_at']; ?></td>
                                            <td class="font-weight-bold text-muted"><?php echo $b['file_name']; ?></td>
                                            <td><span class="badge badge-secondary py-1 px-2"><?php echo $b['file_size']; ?></span></td>
                                            <td class="text-right">
                                                <a href="<?php echo print_link("api/admin/settings/backup/download/" . $b['id']); ?>" class="btn btn-sm btn-outline-success mr-1">
                                                    <i class="fa fa-download"></i> Unduh
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger btn-delete-backup" data-id="<?php echo $b['id']; ?>">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fa fa-info-circle mr-1"></i> Belum ada riwayat cadangan database.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Card List View -->
                        <div class="d-block d-md-none">
                            <?php if (!empty($backups)) {
                                foreach ($backups as $b) { ?>
                                <div class="mobile-backup-card shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted font-weight-bold" style="font-size: 12px;"><?php echo $b['created_at']; ?></span>
                                        <span class="badge badge-secondary"><?php echo $b['file_size']; ?></span>
                                    </div>
                                    <h6 class="font-weight-bold text-dark mb-3"><?php echo $b['file_name']; ?></h6>
                                    <div class="d-flex justify-content-end">
                                        <a href="<?php echo print_link("api/admin/settings/backup/download/" . $b['id']); ?>" class="btn btn-sm btn-success mr-2">
                                            <i class="fa fa-download mr-1"></i> Unduh
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete-backup" data-id="<?php echo $b['id']; ?>">
                                            <i class="fa fa-trash mr-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            <?php } } else { ?>
                                <p class="text-center text-muted py-4"><i class="fa fa-info-circle mr-1"></i> Belum ada riwayat cadangan database.</p>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <!-- TAB 2: SYSTEM CONFIG / ALERT THRESHOLDS -->
                    <div class="tab-pane fade" id="ews" role="tabpanel" aria-labelledby="ews-tab">
                        <div class="mb-4">
                            <h5 class="font-weight-bold text-dark">Ambang Batas Early Warning System (EWS)</h5>
                            <p class="text-muted"><small>Konfigurasikan nilai batas minimal stok obat dan batas maksimal antrean dokter yang akan memicu tanda peringatan darurat pada dashboard utama.</small></p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-5">
                                <form id="threshold-config-form">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-muted" for="min_safety_stock_obat">Batas Minimal Stok Obat (Min Safety Stock)</label>
                                        <div class="input-group">
                                            <input type="number" name="min_safety_stock_obat" id="min_safety_stock_obat" class="form-control" value="<?php echo $min_stock; ?>" required min="1">
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">butir/box</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Batas stok minimum di mana peringatan stok kritis akan ditampilkan.</small>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-muted" for="max_queue_per_doctor">Batas Maksimal Antrean Dokter (Overloaded Doctor Threshold)</label>
                                        <div class="input-group">
                                            <input type="number" name="max_queue_per_doctor" id="max_queue_per_doctor" class="form-control" value="<?php echo $max_queue; ?>" required min="1">
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">pasien</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Jumlah antrean berstatus 'waiting' maksimal per dokter sebelum status beban tinggi berkedip.</small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary font-weight-bold py-2 px-4 shadow-sm" id="btn-save-thresholds">
                                        <i class="fa fa-save mr-1"></i> Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB 3: SYSTEM ACTIVITY LOGS -->
                    <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                        <div class="mb-4">
                            <h5 class="font-weight-bold text-dark">Audit Trail Log Aktivitas</h5>
                            <p class="text-muted"><small>Catatan penelusuran aktivitas penting oleh pengguna (penambahan, modifikasi, penghapusan pasien/dokter/rekam medis/obat) demi menjaga integritas data klinis.</small></p>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                        <th>Deskripsi Aktivitas</th>
                                        <th>Alamat IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($logs)) { 
                                        foreach ($logs as $l) { 
                                            $action_class = "badge-secondary";
                                            if (strpos($l['action'], 'DELETE') !== false) {
                                                $action_class = "badge-danger";
                                            } elseif (strpos($l['action'], 'ADD') !== false) {
                                                $action_class = "badge-success";
                                            } elseif (strpos($l['action'], 'EDIT') !== false) {
                                                $action_class = "badge-warning";
                                            }
                                        ?>
                                        <tr>
                                            <td style="white-space: nowrap;"><span class="text-muted font-weight-bold" style="font-size: 12px;"><?php echo $l['created_at']; ?></span></td>
                                            <td><span class="badge <?php echo $action_class; ?> py-1 px-2" style="font-size: 10px; text-transform: uppercase;"><?php echo $l['action']; ?></span></td>
                                            <td class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo $l['description']; ?></td>
                                            <td><small class="text-muted"><?php echo $l['ip_address']; ?></small></td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fa fa-info-circle mr-1"></i> Belum ada aktivitas yang dicatat.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var csrfToken = '<?php echo $csrf_token; ?>';
    
    // 1. Trigger manual backup creation
    $('#btn-run-backup').on('click', function(e) {
        e.preventDefault();
        
        var btn = $(this);
        var originalBtnHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Membuat Cadangan...');
        
        $.ajax({
            url: siteAddr + 'api/admin/settings/backup/create',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            data: {
                csrf_token: csrfToken
            },
            success: function(res) {
                btn.prop('disabled', false).html(originalBtnHtml);
                if (res.status === 'success') {
                    showToastSuccess(res.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToastDanger(res.message || "Gagal membuat cadangan database.");
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalBtnHtml);
                showToastDanger(xhr.responseJSON ? xhr.responseJSON.message : "Gagal memproses pembuatan cadangan.");
            }
        });
    });
    
    // 2. Delete backup archive
    $(document).on('click', '.btn-delete-backup', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        if (!confirm("Apakah Anda yakin ingin menghapus arsip cadangan database ini? Tindakan ini tidak dapat dibatalkan.")) {
            return;
        }
        
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: siteAddr + 'api/admin/settings/backup/delete/' + id,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            data: {
                csrf_token: csrfToken
            },
            success: function(res) {
                if (res.status === 'success') {
                    showToastSuccess(res.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    btn.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                    showToastDanger(res.message || "Gagal menghapus cadangan database.");
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                showToastDanger(xhr.responseJSON ? xhr.responseJSON.message : "Gagal memproses penghapusan.");
            }
        });
    });
    
    // 3. Save alerts EWS thresholds configuration
    $('#threshold-config-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $('#btn-save-thresholds');
        var originalBtnHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan Konfigurasi...');
        
        var minStock = $('#min_safety_stock_obat').val();
        var maxQueue = $('#max_queue_per_doctor').val();
        
        $.ajax({
            url: siteAddr + 'api/admin/settings/thresholds/save',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            data: {
                min_safety_stock_obat: minStock,
                max_queue_per_doctor: maxQueue,
                csrf_token: csrfToken
            },
            success: function(res) {
                btn.prop('disabled', false).html(originalBtnHtml);
                if (res.status === 'success') {
                    showToastSuccess(res.message);
                } else {
                    showToastDanger(res.message || "Gagal menyimpan konfigurasi.");
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalBtnHtml);
                showToastDanger(xhr.responseJSON ? xhr.responseJSON.message : "Gagal memproses penyimpanan konfigurasi.");
            }
        });
    });
});
</script>
