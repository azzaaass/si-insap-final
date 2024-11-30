<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanBarang extends Model
{
    public function barang(){
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
