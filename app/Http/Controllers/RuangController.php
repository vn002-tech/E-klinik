<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuangController extends Controller
{
    public function index()
    {
        $data = DB::table('ruang')->get();
        return view('ruang.index', compact('data'));
    }
}
