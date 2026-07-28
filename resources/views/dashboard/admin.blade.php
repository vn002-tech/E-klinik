@extends('layouts.app')

@section('content')
<style>
/* Overload Alert Animations */
.pulse-amber {
    animation: pulse-amber-anim 2s infinite;
}
.pulse-red {
    animation: pulse-red-anim 1.5s infinite;
}

@keyframes pulse-amber-anim {
    0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
    100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
}

@keyframes pulse-red-anim {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
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
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title font-weight-bold">Total Antrean Aktif</div>
                            <div class="icon-container"><i class="fa fa-hourglass-half text-primary fa-2x"></i></div>
                        </div>
                        <h2 class="value" id="val-waiting">{{ $waitingCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title font-weight-bold">Dokter Aktif</div>
                            <div class="icon-container text-success"><i class="fa fa-user-md fa-2x"></i></div>
                        </div>
                        <h2 class="value">{{ $doctorCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title font-weight-bold">Total Pasien</div>
                            <div class="icon-container"><i class="fa fa-users text-warning fa-2x"></i></div>
                        </div>
                        <h2 class="value">{{ $patientCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="title font-weight-bold">Rekam Medis (Hari Ini)</div>
                            <div class="icon-container"><i class="fa fa-file-text text-info fa-2x"></i></div>
                        </div>
                        <h2 class="value">{{ $rekamMedisCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surge Control Form -->
        <div class="row mb-4">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                        <i class="fa fa-random text-primary mr-2"></i>
                        <h5 class="m-0 font-weight-bold text-primary">Live Queue Balancing</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Gunakan form ini untuk memindahkan pasien antar dokter.</p>
                        <form action="#" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-muted">Dokter Asal</label>
                                <select name="from_doctor_id" class="form-control" required>
                                    <option value="">Pilih Dokter...</option>
                                    @foreach($doctors as $d)
                                        <option value="{{ $d->id_dokter }}">{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-muted">Pindah Ke Dokter</label>
                                <select name="to_doctor_id" class="form-control" required>
                                    <option value="">Pilih Dokter Tujuan...</option>
                                    @foreach($doctors as $d)
                                        <option value="{{ $d->id_dokter }}">{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 font-weight-bold pulse-amber">
                                <i class="fa fa-exchange mr-1"></i> Alihkan Semua Antrean
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                        <i class="fa fa-bell text-danger mr-2"></i>
                        <h5 class="m-0 font-weight-bold text-danger">Log Sistem (EWS)</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            Semua operasional klinik berjalan normal. Pemantauan antrean real-time aktif.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
