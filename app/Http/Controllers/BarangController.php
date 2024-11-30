<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        $data = [
            'barangs' => $barangs
        ];

        if (Auth::user()->role == 'admin') {
            return view('admin.barang', $data);
        }
        return view('staff.barang', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'stock_in' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/barang', 'public');
            $imageUrl = "/storage/{$path}";
            $validatedData['image_url'] = $imageUrl;
        }

        Barang::create($validatedData);
        return redirect('/barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Barang $barang)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|max:255',
                'stock_in' => 'required|integer|min:0',
                'stock_out' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads/barang', 'public');
                $imageUrl = "/storage/{$path}";
                $validateData['image_url'] = $imageUrl;

                if ($barang->image_url) {
                    $imagePath = public_path($barang->image_url);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            $barang->update($validateData);
        } catch (Exception $e) {
            dd($e);
        }
        return redirect('/barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->image_url) {
            $imagePath = public_path($barang->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $barang->delete();
        return redirect('/barang')->with('success', 'Barang berhasil dihapus.');
    }
}
