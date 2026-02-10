<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tbPengeluaran extends Model
{

    use HasFactory;

    /**
     * Nama tabel (karena tidak mengikuti konvensi Laravel)
     */
    protected $table = 'tbpengeluaran';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'kode_transaksi',
        'nama_transaksi',
        'kategori_id',
        'qty',
        'harga_satuan',
        'total',
        'penerima',
        'deskripsi',
        'bukti_pengeluaran',
        'created_by',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'qty'           => 'integer',
        'harga_satuan'  => 'decimal:2',
        'total'         => 'decimal:2',
    ];

    /* =======================
     | RELATIONSHIPS
     |=======================*/

    /**
     * Relasi ke kategori pengeluaran
     */
    public function kategori()
    {
        return $this->belongsTo(
            KategoriPengeluaran::class,
            'kategori_id'
        );
    }

    /**
     * Relasi ke user pembuat transaksi
     */
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /* =======================
     | ACCESSOR / MUTATOR
     |=======================*/

    /**
     * Otomatis hitung total = qty x harga_satuan
     */
    protected static function booted()
    {
        static::saving(function ($pengeluaran) {
            $pengeluaran->total =
                $pengeluaran->qty * $pengeluaran->harga_satuan;
        });
    }
}
