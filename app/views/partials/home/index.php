<?php 
$page_id = null;
$comp_model = new SharedController;
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;

if (USER_ROLE_NAME == 'super_admin') {
    $db = new PDODb(DB_TYPE, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
    // Fetch initial list of doctors for the dropdown selects
    $doctors = $db->get("dokter", null, "id_dokter, nama");
?>
<style>
/* Overload Alert Animations */
.pulse-amber {
    animation: pulse-amber-anim 2s infinite;
}
.pulse-red {
    animation: pulse-red-anim 1.5s infinite;
}

@keyframes pulse-amber-anim {
    0% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
    }
}

@keyframes pulse-red-anim {
    0% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

/* Flashing indicator dot for emergency */
.dot-flashing {
    position: relative;
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 5px;
    background-color: #ef4444;
    animation: dot-flashing-anim 1s infinite alternate;
    margin-right: 6px;
}

@keyframes dot-flashing-anim {
    0% {
        background-color: rgba(239, 68, 68, 1);
    }
    100% {
        background-color: rgba(239, 68, 68, 0.2);
    }
}

/* Triage badges with curated colors */
.triage-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 50px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    color: white;
}

.triage-emergency {
    background-color: hsl(354, 70%, 54%); /* HSL Red */
}

.triage-urgent {
    background-color: hsl(35, 90%, 50%); /* HSL Orange */
}

.triage-routine {
    background-color: hsl(220, 90%, 56%); /* HSL Blue */
}

/* Stat Cards alert states */
.alert-card-glow-red {
    border: 1px solid rgba(239, 68, 68, 0.5) !important;
    background-color: rgba(254, 242, 242, 0.6) !important;
}

.alert-card-glow-amber {
    border: 1px solid rgba(245, 158, 11, 0.5) !important;
    background-color: rgba(254, 243, 199, 0.6) !important;
}

/* Mobile card format styling */
.mobile-queue-card {
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg-card);
    padding: 15px;
    margin-bottom: 12px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.mobile-queue-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
}
</style>

<div class="border-top pt-3">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-md-12 comp-grid">
                <h4 class="font-weight-bold text-primary text-uppercase tracking-wider">
                    <i class="fa fa-dashboard mr-2"></i>Command Center Dashboard
                </h4>
                <p class="text-muted">Live Queue Control, Early Warning System (EWS), and Real-Time Clinician Routing.</p>
            </div>
        </div>

        <!-- EWS Dynamic Warning Banners -->
        <div id="ews-alerts-container" class="mb-4">
            <div class="alert alert-success d-flex align-items-center border-0 shadow-sm" role="alert" id="ews-operational-banner">
                <i class="fa fa-check-circle mr-2 font-size-md text-success"></i>
                <div>
                    <strong>SISTEM OPERASIONAL NORMAL</strong> - Tidak ada peringatan stok obat kritis atau okupansi ruang berlebih saat ini.
                </div>
            </div>
        </div>

        <!-- Key Metrics Widgets -->
        <div class="row mb-2">
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="dashboard-card" id="widget-waiting">
                    <div class="card-content w-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title">Total Antrean Aktif</div>
                            <div class="icon-container"><i class="fa fa-hourglass-half"></i></div>
                        </div>
                        <h4 class="value" id="val-waiting">0</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="dashboard-card" id="widget-obat">
                    <div class="card-content w-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title">Obat Kritis (EWS)</div>
                            <div class="icon-container text-danger"><i class="fa fa-exclamation-triangle"></i></div>
                        </div>
                        <h4 class="value" id="val-obat">0</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="dashboard-card" id="widget-ruang">
                    <div class="card-content w-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title">Okupansi Kamar</div>
                            <div class="icon-container"><i class="fa fa-percent"></i></div>
                        </div>
                        <h4 class="value" id="val-ruang">0%</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="dashboard-card" id="widget-overloaded">
                    <div class="card-content w-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title">Dokter Overloaded (>8)</div>
                            <div class="icon-container"><i class="fa fa-user-md"></i></div>
                        </div>
                        <h4 class="value" id="val-overloaded">0</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surge Control Form & Resource Monitor EWS Details -->
        <div class="row mb-4">
            <!-- LIVE QUEUE SURGE BALANCING CONTROL -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg" style="background: var(--bg-card); border: 1px solid var(--border) !important;">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                        <i class="fa fa-random text-primary mr-2"></i>
                        <h5 class="m-0 font-weight-bold text-primary">Live Queue Balancing & Triage Routing</h5>
                    </div>
                    <div class="card-body">
                        <form id="reassign-queue-form">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-muted" for="from_doctor_id">Dokter Asal (Overloaded / Sibuk)</label>
                                <select name="from_doctor_id" id="from_doctor_id" class="form-control" required>
                                    <option value="">Pilih Dokter Asal...</option>
                                    <?php foreach ($doctors as $d) { ?>
                                        <option value="<?php echo $d['id_dokter']; ?>"><?php echo $d['nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-muted" for="to_doctor_id">Dokter Tujuan (Tersedia)</label>
                                <select name="to_doctor_id" id="to_doctor_id" class="form-control" required>
                                    <option value="">Pilih Dokter Tujuan...</option>
                                    <?php foreach ($doctors as $d) { ?>
                                        <option value="<?php echo $d['id_dokter']; ?>"><?php echo $d['nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted" for="batch_size">Jumlah Antrean yang Dipindahkan</label>
                                <select name="batch_size" id="batch_size" class="form-control">
                                    <option value="1">1 Pasien (Oldest waiting)</option>
                                    <option value="3">3 Pasien (Oldest waiting)</option>
                                    <option value="5" selected>5 Pasien (Oldest waiting)</option>
                                    <option value="10">10 Pasien (Oldest waiting)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold" id="btn-reassign">
                                <i class="fa fa-random mr-1"></i> Jalankan Penyeimbangan Queue
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EWS RESOURCES REALTIME STATUS LIST -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg" style="background: var(--bg-card); border: 1px solid var(--border) !important;">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                        <i class="fa fa-bell text-danger mr-2"></i>
                        <h5 class="m-0 font-weight-bold text-danger">Resource Alerts & Threshold Monitor (EWS)</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fa fa-building mr-1"></i> Utilisasi Ruangan Klinik</h6>
                        <div id="room-utilization-list" class="mb-4">
                            <p class="text-muted text-center py-2">Loading data ruangan...</p>
                        </div>

                        <h6 class="font-weight-bold text-danger mb-3"><i class="fa fa-medkit mr-1"></i> Stok Obat Kritis</h6>
                        <div id="critical-medicines-list">
                            <p class="text-muted text-center py-2">Loading data obat...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Queue Monitor Board -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-lg" style="background: var(--bg-card); border: 1px solid var(--border) !important;">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-list text-primary mr-2"></i>
                            <h5 class="m-0 font-weight-bold text-primary">Live Queue Monitoring Board (Triage Priority)</h5>
                        </div>
                        <span class="badge badge-info py-2 px-3" style="font-size: 11px;"><i class="fa fa-refresh mr-1"></i> Sync Aktif (10 Detik)</span>
                    </div>
                    <div class="card-body p-0">
                        <!-- DESKTOP VIEW TABLE -->
                        <div class="d-none d-md-block table-responsive">
                            <table class="table table-hover table-striped m-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID Medis</th>
                                        <th>Tanggal Periksa</th>
                                        <th>Nama Pasien</th>
                                        <th>Keluhan</th>
                                        <th>Dokter Pemeriksa</th>
                                        <th>Ruangan</th>
                                        <th>Triage Level</th>
                                        <th>Status Antrean</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="desktop-queue-body">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Memuat data antrean...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- MOBILE VIEW CARDS -->
                        <div class="d-block d-md-none p-3" id="mobile-queue-cards">
                            <p class="text-center text-muted py-3">Memuat data antrean...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 1. First initial metrics call
    loadLiveQueueBoard();

    // 2. Poll metrics every 10 seconds (10000 ms)
    setInterval(loadLiveQueueBoard, 10000);

    function loadLiveQueueBoard() {
        $.ajax({
            url: siteAddr + 'api/admin/command-center/metrics',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                updateWidgets(data);
                updateEWSBanners(data);
                updateLiveQueueForm(data);
                updateEWSLists(data);
                updateQueueBoard(data);
            },
            error: function(xhr, err) {
                console.error("Gagal mengambil metrics Command Center: ", xhr.statusText);
            }
        });
    }

    function updateWidgets(data) {
        // Active Queue Count
        var activeCount = data.active_queue ? data.active_queue.length : 0;
        $('#val-waiting').text(activeCount);

        // Low stock count
        var lowStockCount = data.critical_medicines ? data.critical_medicines.length : 0;
        $('#val-obat').text(lowStockCount);
        if (lowStockCount > 0) {
            $('#widget-obat').addClass('alert-card-glow-red pulse-red');
        } else {
            $('#widget-obat').removeClass('alert-card-glow-red pulse-red');
        }

        // Room Utilization
        var util = data.room_metrics ? data.room_metrics.overall_utilization : 0;
        $('#val-ruang').text(util + '%');
        if (util >= 90) {
            $('#widget-ruang').addClass('alert-card-glow-red pulse-red').removeClass('alert-card-glow-amber pulse-amber');
        } else if (util >= 75) {
            $('#widget-ruang').addClass('alert-card-glow-amber pulse-amber').removeClass('alert-card-glow-red pulse-red');
        } else {
            $('#widget-ruang').removeClass('alert-card-glow-red alert-card-glow-amber pulse-red pulse-amber');
        }

        // Overloaded Doctors Count
        var overloadedCount = data.overloaded_doctors ? data.overloaded_doctors.length : 0;
        $('#val-overloaded').text(overloadedCount);
        if (overloadedCount > 0) {
            $('#widget-overloaded').addClass('alert-card-glow-amber pulse-amber');
        } else {
            $('#widget-overloaded').removeClass('alert-card-glow-amber pulse-amber');
        }
    }

    function updateEWSBanners(data) {
        var alerts = [];
        
        // Critical Drugs
        if (data.critical_medicines && data.critical_medicines.length > 0) {
            data.critical_medicines.forEach(function(o) {
                alerts.push({
                    type: 'danger',
                    text: '<strong>KRITIS STOK OBAT</strong>: Obat ' + o.nama_obat + ' tinggal ' + o.stok + ' butir (Batas minimum: ' + o.min_safety_threshold + ').',
                    icon: 'fa-exclamation-triangle'
                });
            });
        }

        // Overloaded Rooms
        if (data.room_metrics && data.room_metrics.critical_rooms && data.room_metrics.critical_rooms.length > 0) {
            data.room_metrics.critical_rooms.forEach(function(r) {
                alerts.push({
                    type: 'danger',
                    text: '<strong>OVERKAPASITAS RUANG</strong>: Ruangan ' + r.nama_ruang + ' mencapai ' + r.utilization + '% kapasitas (' + r.current_occupancy + '/' + r.max_capacity + ' pasien).',
                    icon: 'fa-bed'
                });
            });
        }

        // Overloaded Doctors
        if (data.overloaded_doctors && data.overloaded_doctors.length > 0) {
            data.overloaded_doctors.forEach(function(d) {
                alerts.push({
                    type: 'warning',
                    text: '<strong>BEBAN ANTRIAN TINGGI</strong>: Dokter ' + d.nama + ' memiliki ' + d.waiting_count + ' pasien menunggu di antrean.',
                    icon: 'fa-user-md'
                });
            });
        }

        var container = $('#ews-alerts-container');
        container.empty();

        if (alerts.length > 0) {
            alerts.forEach(function(a) {
                var alertClass = a.type === 'danger' ? 'alert-danger pulse-red' : 'alert-warning pulse-amber';
                var alertHtml = '<div class="alert ' + alertClass + ' border-0 d-flex align-items-center mb-2 shadow-sm" role="alert">' +
                                    '<i class="fa ' + a.icon + ' mr-2 font-size-md"></i>' +
                                    '<div>' + a.text + '</div>' +
                                '</div>';
                container.append(alertHtml);
            });
        } else {
            var normalHtml = '<div class="alert alert-success d-flex align-items-center border-0 shadow-sm" role="alert" id="ews-operational-banner">' +
                                '<i class="fa fa-check-circle mr-2 font-size-md text-success"></i>' +
                                '<div><strong>SISTEM OPERASIONAL NORMAL</strong> - Tidak ada peringatan stok obat kritis atau okupansi ruang berlebih saat ini.</div>' +
                                '</div>';
            container.append(normalHtml);
        }
    }

    function updateLiveQueueForm(data) {
        if (!data.all_doctors) return;
        
        var fromSelect = $('#from_doctor_id');
        var toSelect = $('#to_doctor_id');
        
        var fromVal = fromSelect.val();
        var toVal = toSelect.val();
        
        fromSelect.html('<option value="">Pilih Dokter Asal...</option>');
        toSelect.html('<option value="">Pilih Dokter Tujuan...</option>');
        
        data.all_doctors.forEach(function(d) {
            var optionText = d.nama + ' (' + d.waiting_count + ' antrean waiting)';
            fromSelect.append($('<option>', {value: d.id_dokter, text: optionText}));
            toSelect.append($('<option>', {value: d.id_dokter, text: optionText}));
        });
        
        if (fromVal) fromSelect.val(fromVal);
        if (toVal) toSelect.val(toVal);
    }

    function updateEWSLists(data) {
        // Room Capacity Monitor
        var roomContainer = $('#room-utilization-list');
        roomContainer.empty();
        if (data.room_metrics && data.room_metrics.all_rooms && data.room_metrics.all_rooms.length > 0) {
            data.room_metrics.all_rooms.forEach(function(r) {
                var ratio = r.max_capacity > 0 ? (r.current_occupancy / r.max_capacity) : 0;
                var pct = Math.round(ratio * 100);
                var barColor = 'bg-primary';
                var badgeColor = 'badge-primary';
                
                if (pct >= 90) {
                    barColor = 'bg-danger';
                    badgeColor = 'badge-danger';
                } else if (pct >= 75) {
                    barColor = 'bg-warning';
                    badgeColor = 'badge-warning';
                }
                
                var roomHtml = '<div class="mb-3">' +
                                    '<div class="d-flex justify-content-between mb-1" style="font-size: 13px;">' +
                                        '<span>' + r.nama_ruang + '</span>' +
                                        '<span class="font-weight-bold text-muted">' + r.current_occupancy + '/' + r.max_capacity + ' Kapasitas <span class="badge ' + badgeColor + ' ml-1">' + pct + '%</span></span>' +
                                    '</div>' +
                                    '<div class="progress" style="height: 6px; border-radius: 3px;">' +
                                        '<div class="progress-bar ' + barColor + '" role="progressbar" style="width: ' + pct + '%"></div>' +
                                    '</div>' +
                                '</div>';
                roomContainer.append(roomHtml);
            });
        } else {
            roomContainer.append('<p class="text-muted text-center py-2">Tidak ada data ruangan.</p>');
        }

        // Low stock drug list
        var obatContainer = $('#critical-medicines-list');
        obatContainer.empty();
        if (data.critical_medicines && data.critical_medicines.length > 0) {
            data.critical_medicines.forEach(function(o) {
                var ratio = o.min_safety_threshold > 0 ? (o.stok / o.min_safety_threshold) : 0;
                var pct = Math.min(Math.round(ratio * 100), 100);
                var barColor = o.stok <= (o.min_safety_threshold / 2) ? 'bg-danger' : 'bg-warning';
                
                var obatHtml = '<div class="mb-3">' +
                                    '<div class="d-flex justify-content-between mb-1" style="font-size: 13px;">' +
                                        '<span>' + o.nama_obat + '</span>' +
                                        '<span class="font-weight-bold text-danger">Stok: ' + o.stok + ' <small class="text-muted">(Batas safety: ' + o.min_safety_threshold + ')</small></span>' +
                                    '</div>' +
                                    '<div class="progress" style="height: 6px; border-radius: 3px;">' +
                                        '<div class="progress-bar ' + barColor + '" role="progressbar" style="width: ' + pct + '%"></div>' +
                                    '</div>' +
                                '</div>';
                obatContainer.append(obatHtml);
            });
        } else {
            obatContainer.append('<p class="text-success text-center font-weight-bold py-2 mb-0"><i class="fa fa-check-circle mr-1"></i> Semua Stok Obat Aman</p>');
        }
    }

    function updateQueueBoard(data) {
        var desktopBody = $('#desktop-queue-body');
        var mobileCards = $('#mobile-queue-cards');
        desktopBody.empty();
        mobileCards.empty();

        if (data.active_queue && data.active_queue.length > 0) {
            data.active_queue.forEach(function(item) {
                var triageClass = 'triage-routine';
                var triageDot = '';
                if (item.triage_level === 'emergency') {
                    triageClass = 'triage-emergency';
                    triageDot = '<span class="dot-flashing"></span>';
                } else if (item.triage_level === 'urgent') {
                    triageClass = 'triage-urgent';
                }
                
                var statusClass = 'badge-secondary';
                if (item.status_antrean === 'waiting') {
                    statusClass = 'badge-warning';
                } else if (item.status_antrean === 'processing') {
                    statusClass = 'badge-primary';
                } else if (item.status_antrean === 'completed') {
                    statusClass = 'badge-success';
                }

                // Desktop Row
                var rowHtml = '<tr>' +
                                '<td><a class="font-weight-bold text-decoration-none" href="' + siteAddr + 'rekam_medis/view/' + item.id_medis + '">#' + item.id_medis + '</a></td>' +
                                '<td>' + item.tanggal_periksa + '</td>' +
                                '<td>' + item.nama_pasien + '</td>' +
                                '<td style="max-width: 200px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">' + (item.keluhan || '-') + '</td>' +
                                '<td>' + (item.dokter_nama || '-') + '</td>' +
                                '<td>' + (item.ruang_nama || '-') + '</td>' +
                                '<td><span class="triage-badge ' + triageClass + '">' + triageDot + item.triage_level + '</span></td>' +
                                '<td><span class="badge ' + statusClass + ' py-1 px-2 text-capitalize">' + item.status_antrean + '</span></td>' +
                                '<td>' +
                                    '<div class="dropdown">' +
                                        '<button class="btn btn-sm btn-outline-primary dropdown-toggle py-0 px-2" type="button" data-toggle="dropdown">' +
                                            'Pilih' +
                                        '</button>' +
                                        '<div class="dropdown-menu dropdown-menu-right">' +
                                            '<a class="dropdown-item py-1" href="' + siteAddr + 'rekam_medis/view/' + item.id_medis + '"><i class="fa fa-eye mr-2 text-muted"></i> Detail</a>' +
                                            '<a class="dropdown-item py-1" href="' + siteAddr + 'rekam_medis/edit/' + item.id_medis + '"><i class="fa fa-edit mr-2 text-muted"></i> Edit</a>' +
                                            '<a class="dropdown-item py-1 text-primary alihkan-antrean-btn" href="#" data-doctor-id="' + item.id_dokter + '"><i class="fa fa-random mr-2"></i> Alihkan Antrean</a>' +
                                            '<div class="dropdown-divider"></div>' +
                                            '<a class="dropdown-item py-1 text-danger record-delete-btn" href="' + siteAddr + 'rekam_medis/delete/' + item.id_medis + '?csrf_token=' + csrfToken + '&redirect=home" data-prompt-msg="Apakah Anda yakin ingin menghapus rekam medis ini?" data-display-style="modal"><i class="fa fa-trash mr-2"></i> Hapus</a>' +
                                        '</div>' +
                                    '</div>' +
                                '</td>' +
                            '</tr>';
                desktopBody.append(rowHtml);

                // Mobile Card
                var cardHtml = '<div class="mobile-queue-card shadow-sm">' +
                                    '<div class="d-flex justify-content-between align-items-center mb-2">' +
                                        '<a href="' + siteAddr + 'rekam_medis/view/' + item.id_medis + '" class="font-weight-bold text-decoration-none">#' + item.id_medis + '</a>' +
                                        '<span class="triage-badge ' + triageClass + '">' + triageDot + item.triage_level + '</span>' +
                                    '</div>' +
                                    '<h6 class="font-weight-bold text-primary mb-2">' + item.nama_pasien + '</h6>' +
                                    '<p class="mb-2 text-muted" style="font-size: 13px;"><strong>Keluhan:</strong> ' + (item.keluhan || '-') + '</p>' +
                                    '<div class="row text-muted mb-2" style="font-size: 12px;">' +
                                        '<div class="col-6"><strong>Dokter:</strong> ' + (item.dokter_nama || '-') + '</div>' +
                                        '<div class="col-6"><strong>Ruangan:</strong> ' + (item.ruang_nama || '-') + '</div>' +
                                    '</div>' +
                                    '<div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">' +
                                        '<div><span class="badge ' + statusClass + ' py-1 px-2 text-capitalize">' + item.status_antrean + '</span></div>' +
                                        '<div class="dropdown">' +
                                            '<button class="btn btn-sm btn-outline-primary dropdown-toggle py-0 px-2" type="button" data-toggle="dropdown">' +
                                                'Pilih' +
                                            '</button>' +
                                            '<div class="dropdown-menu dropdown-menu-right">' +
                                                '<a class="dropdown-item py-1" href="' + siteAddr + 'rekam_medis/view/' + item.id_medis + '"><i class="fa fa-eye mr-2 text-muted"></i> Detail</a>' +
                                                '<a class="dropdown-item py-1" href="' + siteAddr + 'rekam_medis/edit/' + item.id_medis + '"><i class="fa fa-edit mr-2 text-muted"></i> Edit</a>' +
                                                '<a class="dropdown-item py-1 text-primary alihkan-antrean-btn" href="#" data-doctor-id="' + item.id_dokter + '"><i class="fa fa-random mr-2"></i> Alihkan Antrean</a>' +
                                                '<div class="dropdown-divider"></div>' +
                                                '<a class="dropdown-item py-1 text-danger record-delete-btn" href="' + siteAddr + 'rekam_medis/delete/' + item.id_medis + '?csrf_token=' + csrfToken + '&redirect=home" data-prompt-msg="Apakah Anda yakin ingin menghapus rekam medis ini?" data-display-style="modal"><i class="fa fa-trash mr-2"></i> Hapus</a>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';
                mobileCards.append(cardHtml);
            });
        } else {
            var emptyHtml = '<tr><td colspan="9" class="text-center text-muted py-4"><i class="fa fa-ban mr-1"></i> Tidak ada antrean aktif saat ini.</td></tr>';
            desktopBody.append(emptyHtml);
            mobileCards.append('<p class="text-center text-muted py-4"><i class="fa fa-ban mr-1"></i> Tidak ada antrean aktif saat ini.</p>');
        }
    }

    // Submit reassignment via AJAX
    $('#reassign-queue-form').on('submit', function(e) {
        e.preventDefault();
        
        var fromDoc = $('#from_doctor_id').val();
        var toDoc = $('#to_doctor_id').val();
        var batch = $('#batch_size').val();

        if (fromDoc === toDoc) {
            alert("Dokter asal dan dokter tujuan pemindahan tidak boleh sama!");
            return;
        }

        var btn = $('#btn-reassign');
        var originalBtnHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Memindahkan Antrean...');

        $.ajax({
            url: siteAddr + 'api/admin/command-center/reassign-batch',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            data: {
                from_doctor_id: fromDoc,
                to_doctor_id: toDoc,
                batch_size: batch,
                csrf_token: csrfToken
            },
            success: function(res) {
                btn.prop('disabled', false).html(originalBtnHtml);
                if (res.status === 'success') {
                    showToastSuccess(res.message);
                    loadLiveQueueBoard();
                    $('#from_doctor_id').val('');
                    $('#to_doctor_id').val('');
                } else {
                    showToastDanger(res.message || "Gagal melakukan pemindahan antrean.");
                }
            },
            error: function(xhr, err) {
                btn.prop('disabled', false).html(originalBtnHtml);
                showToastDanger(xhr.responseJSON ? xhr.responseJSON.message : "Gagal memproses pemindahan antrean.");
            }
        });
    });

    // Trigger reassignment auto-fill and scroll
    $(document).on('click', '.alihkan-antrean-btn', function(e) {
        e.preventDefault();
        var docId = $(this).data('doctor-id');
        if (docId) {
            $('#from_doctor_id').val(docId).trigger('change');
            $('html, body').animate({
                scrollTop: $('#reassign-queue-form').offset().top - 80
            }, 600);
            $('#from_doctor_id').focus();
            
            // Highlight the form briefly to guide user
            var formCard = $('#reassign-queue-form').closest('.card');
            formCard.addClass('pulse-amber');
            setTimeout(function() {
                formCard.removeClass('pulse-amber');
            }, 2000);
        }
    });
});
</script>

<?php
} else {
    // Normal Dashboard for Pasien or Dokter (Scoped Visibility)
?>
<div>
    <div class="border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12 comp-grid mb-4 pt-3">
                    <h4 class="font-weight-bold text-primary">Dashboard Utama</h4>
                    <p class="text-muted">Ringkasan statistik dan aktivitas klinik hari ini.</p>
                </div>
                <div class="col-md-3 col-sm-6 comp-grid mb-4">
                    <a class="dashboard-card" href="<?php print_link("rekam_medis/") ?>">
                        <div class="card-content w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="title">Rekam Medis</div>
                                <div class="icon-container"><i class="fa fa-database"></i></div>
                            </div>
                            <h4 class="value"><?php echo $comp_model->getcount_rekammedis(); ?></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 comp-grid mb-4">
                    <a class="dashboard-card" href="<?php print_link("dokter/") ?>">
                        <div class="card-content w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="title">Dokter</div>
                                <div class="icon-container"><i class="fa fa-user-md"></i></div>
                            </div>
                            <h4 class="value"><?php echo $comp_model->getcount_dokter(); ?></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 comp-grid mb-4">
                    <a class="dashboard-card" href="<?php print_link("pasien/") ?>">
                        <div class="card-content w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="title">Pasien</div>
                                <div class="icon-container"><i class="fa fa-user"></i></div>
                            </div>
                            <h4 class="value"><?php echo $comp_model->getcount_pasien(); ?></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 comp-grid mb-4">
                    <a class="dashboard-card" href="<?php print_link("pengguna/") ?>">
                        <div class="card-content w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="title">Pengguna</div>
                                <div class="icon-container"><i class="fa fa-users"></i></div>
                            </div>
                            <h4 class="value"><?php echo $comp_model->getcount_pengguna(); ?></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-12 comp-grid">
                    <div class="card dashboard-card w-100 p-0 mt-4">
                        <div class="card-body p-0">
                        <?php  
                        $this->render_page("rekam_medis/list?orderby=rekam_medis.tanggal_periksa&ordertype=DESC&limit_count=20"); 
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
} 
?>
