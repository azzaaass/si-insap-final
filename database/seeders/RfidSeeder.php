<?php

namespace Database\Seeders;

use App\Models\Rfid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RfidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rfid::create([
            'name' => 'FA01'
        ]);
        Rfid::create([
            'name' => 'FA02'
        ]);
        Rfid::create([
            'name' => 'FA03'
        ]);
        Rfid::create([
            'name' => 'FA04'
        ]);
        Rfid::create([
            'name' => 'FA05'
        ]);
    }
}
