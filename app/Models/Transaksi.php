<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_mobil',
        'id_peminjam',
        'id_cabang_pickup',
        'id_cabang_dropoff',
        'waktu_pickup',
        'waktu_dropoff',
        'metode_pembayaran',
        'details',
        'mobil',
        'user',
        'pickup',
        'dropoff',
        'total',
    ];

    public function mobil()
    {
        $this->belongsTo(Mobil::class, 'id_mobil');
    }
    public function user()
    {
        $this->belongsTo(User::class, 'id_peminjam');
    }
    public function pickup()
    {
        $this->belongsTo(Cabang::class, 'id_cabang_pickup');
    }
    public function dropoff()
    {
        $this->belongsTo(Cabang::class, 'id_cabang_dropoff');
    }
}
