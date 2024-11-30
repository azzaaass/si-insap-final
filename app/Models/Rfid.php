<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    protected $guarded = ['id'];

    public function logs(){
        return $this->hasMany(LogRfid::class, 'rfid_id', 'id');
    }
}
