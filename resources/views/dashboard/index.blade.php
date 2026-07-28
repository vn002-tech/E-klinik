@extends('layouts.app')

@section('content')
<div class="border-top pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 comp-grid mb-4 pt-3">
                <h4 class="font-weight-bold text-primary">Dashboard Utama</h4>
                <p class="text-muted">Ringkasan statistik dan aktivitas klinik hari ini.</p>
            </div>
            
            <div class="col-md-3 col-sm-6 comp-grid mb-4">
                <div class="card shadow-sm border-0 h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="font-weight-bold">Rekam Medis</div>
                        <div class="icon-container"><i class="fa fa-database text-primary fa-2x"></i></div>
                    </div>
                    <h2 class="value m-0">{{ $rekamMedisCount ?? 0 }}</h2>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 comp-grid mb-4">
                <div class="card shadow-sm border-0 h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="font-weight-bold">Dokter</div>
                        <div class="icon-container"><i class="fa fa-user-md text-success fa-2x"></i></div>
                    </div>
                    <h2 class="value m-0">{{ $doctorCount ?? 0 }}</h2>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 comp-grid mb-4">
                <div class="card shadow-sm border-0 h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="font-weight-bold">Pasien</div>
                        <div class="icon-container"><i class="fa fa-user text-warning fa-2x"></i></div>
                    </div>
                    <h2 class="value m-0">{{ $patientCount ?? 0 }}</h2>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 comp-grid mb-4">
                <div class="card shadow-sm border-0 h-100 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="font-weight-bold">Pengguna</div>
                        <div class="icon-container"><i class="fa fa-users text-info fa-2x"></i></div>
                    </div>
                    <h2 class="value m-0">{{ $penggunaCount ?? 0 }}</h2>
                </div>
            </div>
            
            <div class="col-md-12 comp-grid">
                <div class="card border-0 shadow-sm w-100 mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Rekam Medis Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Rekam Medis</th>
                                        <th>Pasien</th>
                                        <th>Tanggal Periksa</th>
                                        <th>Keluhan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRecords ?? [] as $record)
                                    <tr>
                                        <td>{{ $record->no_rekam_medis }}</td>
                                        <td>{{ $record->nama_pasien }}</td>
                                        <td>{{ date('d-m-Y', strtotime($record->tanggal_periksa)) }}</td>
                                        <td>{{ $record->keluhan }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada data rekam medis.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
