<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index(){
        $pengadaans = Pengadaan::all();
        $data = [
            'pengadaans' => $pengadaans
        ];
        return view('admin.pengadaan', $data);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'message' => 'nullable|string',
        ]);
        
        Pengadaan::create($validatedData);
        return redirect('/pengadaan')->with('success', 'Pengadaan barang berhasil ditambahkan.');
    }
}
