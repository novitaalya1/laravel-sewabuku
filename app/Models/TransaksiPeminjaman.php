<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPeminjaman extends Model
{
    protected $table = 'transaksi_peminjaman';
    public function peminjam(){
        return $this->belongsTo(Peminjam::class, 'id_peminjam');
    }
    public function buku(){
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
