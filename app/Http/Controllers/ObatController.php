<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObatController extends Controller
{
    public function index()
    {
        $data = DB::table('obat')->get();
        return view('obat.index', compact('data'));
    }
}
