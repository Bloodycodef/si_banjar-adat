<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriPemasukan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pemasukan';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi: 1 kategori punya banyak pemasukan
     */
    public function pemasukan()
    {
        return $this->hasMany(tbPemasukan::class, 'kategori_id');
    }
}
