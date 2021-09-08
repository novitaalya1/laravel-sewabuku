<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    protected $table = 'peminjam';

    public function telepon(){
        return $this->hasOne(Telepon::class, 'id_peminjam');
    }
    public function jenis_peminjam(){
        return $this->belongsTo(JenisPeminjam::class, 'id_jenis_peminjam');
    }
    public function buku(){
        return $this->belongsToMany(Buku::class, 'transaksi_peminjaman','id_peminjam','id_buku');
    }
    public function transaksi_peminjaman(){
        return $this->hasMany(TransaksiPeminjaman::class, 'id_peminjam');
    }
}
