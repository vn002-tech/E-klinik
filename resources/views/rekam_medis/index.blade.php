@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Data Rekam Medis</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal</th>
<th>Pasien</th>
<th>Dokter</th>
<th>Keluhan</th>
<th>Diagnosa</th>
<th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ date("d/m/Y", strtotime($row->tanggal_periksa)) }}</td>
<td>{{ $row->nama_pasien }}</td>
<td>{{ $row->nama_dokter }}</td>
<td>{{ $row->keluhan }}</td>
<td>{{ $row->diagnosa }}</td>
<td>{{ $row->status_antrean }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
