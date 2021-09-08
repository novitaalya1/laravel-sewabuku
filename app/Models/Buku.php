<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    //use HasFactory;

    protected $table = 'buku';
    protected $fillable = ['judul_buku','jmlh_halaman','ISBN','pengarang','tahun_terbit'];

    public function peminjam(){
        return $this->belongsToMany(Peminjam::class, 'transaksi_peminjaman','id_buku','id_peminjam');
    }
    public function transaksi_peminjaman(){
        return $this->hasMany(TransaksiPeminjaman::class, 'id_buku');
    }
}
