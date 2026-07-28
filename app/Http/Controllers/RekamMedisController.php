<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index()
    {
        $data = DB::table('rekam_medis')->leftJoin('pasien', 'rekam_medis.id_pasien', '=', 'pasien.id_pasien')->leftJoin('dokter', 'rekam_medis.id_dokter', '=', 'dokter.id_dokter')->select('rekam_medis.*', 'pasien.nama_pasien', 'dokter.nama as nama_dokter')->orderBy('tanggal_periksa', 'desc')->get();
        return view('rekam_medis.index', compact('data'));
    }
}
