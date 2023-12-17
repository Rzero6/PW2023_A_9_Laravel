<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_cabang',
        'tipe',
        'nama',
        'harga_sewa',
        'tahun',
        'bahan_bakar',
        'jml_tempat_duduk',
        'transmisi',
        'no_polisi',
        'image',
        'disewa',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }
}
