<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    public function index()
    {
        $data = DB::table('dokter')->get();
        return view('dokter.index', compact('data'));
    }
}
