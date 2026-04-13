<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'buku_id',
        'jumlah',
        'durasi_hari',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'status',
        'status_approval',
        'denda',
        'kondisi_buku',
        'catatan_kondisi',
        'denda_kerusakan',
        'status_bayar_denda',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
