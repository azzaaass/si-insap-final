<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    protected $guarded = ['id'];

    public function staff(){
        return $this->belongsTo(User::class, 'staff_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function rfid(){
        return $this->belongsTo(Rfid::class, 'rfid_id', 'id');
    }

    public function detailPeminjamans(){
        return $this->hasMany(DetailPeminjamanBarang::class, 'peminjaman_barang_id', 'id');
    }
}
