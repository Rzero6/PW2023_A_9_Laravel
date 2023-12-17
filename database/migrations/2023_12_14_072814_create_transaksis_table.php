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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mobil')->constrained('mobils', 'id')->onDelete('no action');
            $table->foreignId('id_peminjam')->constrained('users', 'id')->onDelete('no action');
            $table->foreignId('id_cabang_pickup')->constrained('cabangs', 'id')->onDelete('no action');
            $table->foreignId('id_cabang_dropoff')->constrained('cabangs', 'id')->onDelete('no action');
            $table->date('waktu_pickup');
            $table->date('waktu_dropoff');
            $table->string('metode_pembayaran');
            $table->string('details');
            $table->string('mobil');
            $table->string('user');
            $table->string('pickup');
            $table->string('dropoff');
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
