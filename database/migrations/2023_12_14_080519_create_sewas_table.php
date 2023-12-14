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
        Schema::create('sewas', function (Blueprint $table) {
            $table->id();
            $table->integer("fk_id_pelanggan");
            $table->integer("fk_id_mobil");
            $table->string("lokasi_pick_up");
            $table->string("lokasi_drop_off");
            $table->integer("waktu_pick_up");
            $table->integer("waktu_drop_off");
            $table->integer("total_bayar");
            $table->string("metode_pembayaran");
            $table->integer("tanggal_pesan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sewas');
    }
};
