<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggunaController extends Controller
{
    public function index()
    {
        $data = DB::table('pengguna')->select('id_pengguna', 'username', 'nama', 'jabatan', 'email', 'user_role_id')->get();
        return view('pengguna.index', compact('data'));
    }
}
