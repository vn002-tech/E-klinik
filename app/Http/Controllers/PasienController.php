<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    public function index()
    {
        $data = DB::table('pasien')->get();
        return view('pasien.index', compact('data'));
    }
}
