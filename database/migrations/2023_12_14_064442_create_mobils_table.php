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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cabang')->constrained('cabangs', 'id')->onDelete('no action');
            $table->string("tipe");
            $table->string("nama");
            $table->double("harga_sewa");
            $table->integer("tahun");
            $table->string("bahan_bakar");
            $table->integer("jml_tempat_duduk");
            $table->string("transmisi");
            $table->string("no_polisi");
            $table->string('image');
            $table->boolean("disewa");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
