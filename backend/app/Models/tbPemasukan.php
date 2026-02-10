<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbPemasukan extends Model
{
    use HasFactory;

    /**
     * Karena nama tabel tidak mengikuti konvensi Laravel
     */
    protected $table = 'tbpemasukan';

    /**
     * Kolom yang boleh diisi mass assignment
     */
    protected $fillable = [
        'kode_transaksi',
        'nama_transaksi',
        'kategori_id',
        'qty',
        'harga_satuan',
        'total',
        'sumber_dana',
        'deskripsi',
        'bukti_pembayaran',
        'created_by',
    ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /* =========================
       RELATIONSHIP
    ========================= */

    /**
     * Relasi ke kategori pemasukan
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriPemasukan::class, 'kategori_id');
    }

    /**
     * Relasi ke user pembuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* =========================
       MODEL EVENT
    ========================= */

    /**
     * Hitung total otomatis (AMAN & KONSISTEN)
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->total = $model->qty * $model->harga_satuan;
        });

        static::updating(function ($model) {
            $model->total = $model->qty * $model->harga_satuan;
        });
    }
}
