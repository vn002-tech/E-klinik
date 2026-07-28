<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cek jika user adalah super_admin (role_id = 1)
        if ($user->user_role_id == 1) {
            
            // Karena tabel antrean tidak ada, kita atur ke 0 sementara
            $waitingCount = 0;
                
            $doctorCount = DB::table('dokter')->count();
            $patientCount = DB::table('pasien')->count();
            $rekamMedisCount = DB::table('rekam_medis')
                ->whereDate('tanggal_periksa', date('Y-m-d'))
                ->count();
                
            $doctors = DB::table('dokter')->select('id_dokter', 'nama')->get();

            return view('dashboard.admin', compact(
                'waitingCount', 
                'doctorCount', 
                'patientCount', 
                'rekamMedisCount',
                'doctors'
            ));
        }

        // Tampilan untuk user biasa (pasien, dokter, dll)
        $rekamMedisCount = DB::table('rekam_medis')->count();
        $doctorCount = DB::table('dokter')->count();
        $patientCount = DB::table('pasien')->count();
        $penggunaCount = DB::table('pengguna')->count();

        // Join dengan tabel pasien jika diperlukan untuk nama pasien
        $recentRecords = DB::table('rekam_medis')
            ->leftJoin('pasien', 'rekam_medis.id_pasien', '=', 'pasien.id_pasien')
            ->select('rekam_medis.no_rekam_medis', 'rekam_medis.tanggal_periksa', 'rekam_medis.keluhan', 'pasien.nama_pasien')
            ->orderBy('rekam_medis.tanggal_periksa', 'desc')
            ->limit(20)
            ->get();

        return view('dashboard.index', compact(
            'rekamMedisCount',
            'doctorCount',
            'patientCount',
            'penggunaCount',
            'recentRecords'
        ));
    }
}
