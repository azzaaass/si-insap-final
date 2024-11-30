<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjamanBarang;
use App\Models\PeminjamanBarang;
use App\Models\Rfid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanBarangController extends Controller
{
    public function index()
    {
        $peminjamanBarangs = PeminjamanBarang::with('staff')->with('detailPeminjamans.barang')->with('rfid')->get();
        $barangs = Barang::all();
        $rfids = Rfid::all();

        $data = [
            'peminjamanBarangs' => $peminjamanBarangs,
            'barangs' => $barangs,
            'rfids' => $rfids
        ];

        if (Auth::user()->role == 'admin') {
            return view('admin.peminjaman', $data);
        }
        return view('staff.peminjaman', $data);
    }

    public function store(Request $request)
    {
        // staff create peminjaman
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'staff_id' => 'required|exists:users,id',
            'catatan_peminjaman' => 'nullable|string',
        ]);

        $validatedData['status'] = 'Admin';
        $peminjaman = PeminjamanBarang::create($validatedData);

        $barangIds = $request->barang_id;
        $barangJumlahs = $request->barang_jumlah;

        $details = [];
        foreach ($barangIds as $index => $barangId) {
            $details[] = [
                'peminjaman_barang_id' => $peminjaman->id,
                'barang_id' => $barangId,
                'jumlah' => $barangJumlahs[$index],
            ];
            $barang = Barang::find($barangId);
            $barang->stock_out += $barangJumlahs[$index];
            $barang->save();
        }
        DetailPeminjamanBarang::insert($details);
        return redirect('/peminjaman')->with('success', 'Peminjaman barang berhasil ditambahkan.');
    }

    public function update(Request $request, PeminjamanBarang $peminjaman)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'catatan_peminjaman' => 'nullable|string',
            'staff_id' => 'nullable|exists:users,id',
            'admin_id' => 'nullable|exists:users,id',
            'rfid_id' => 'nullable|exists:rfids,id',
            'status' => 'nullable|string',
            'catatan_pengembalian' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_url_pengembalian' => 'nullable|string'
        ]);


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/barang', 'public');
            $imageUrl = "/storage/{$path}";
            $validatedData['image_url_pengembalian'] = $imageUrl;
            $validatedData['status'] = 'Selesai';

            $detailBarangs = DetailPeminjamanBarang::where('peminjaman_barang_id', $peminjaman->id)->get();
            foreach ($detailBarangs as $detailBarang) {
                $barang = Barang::find($detailBarang->barang_id);
                $barang->stock_out -= $detailBarang->jumlah;
                $barang->save();
            }
        } 

        $peminjaman->update($validatedData);
        return redirect('/peminjaman')->with('success', 'Peminjaman barang berhasil diperbarui.');
    }
}
