<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('catatan_peminjaman')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->integer('rfid_id')->nullable();
            $table->enum('status', ['Admin', 'Proses', 'Selesai', 'Ditolak'])->default('Admin');
            $table->string('catatan_pengembalian')->nullable();
            $table->string('image_url_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barangs');
    }
};
