<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_mobil',
        'id_user',
        'id_transaksi',
        'rating',
        'komen',
    ];

    public function mobil()
    {
        $this->belongsTo(Mobil::class, 'id_mobil');
    }
    public function user()
    {
        $this->belongsTo(User::class, 'id_user');
    }
    public function transaksi()
    {
        $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
