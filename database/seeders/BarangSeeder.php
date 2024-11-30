<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barang::create([
            'name' => 'Baju',
            'stock_in' => 10,
            'stock_out' => 0,
            'image_url' => 'ppp'
        ]);
        Barang::create([
            'name' => 'Kursi',
            'stock_in' => 6,
            'stock_out' => 0,
            'image_url' => 'ppp'
        ]);
    }
}
