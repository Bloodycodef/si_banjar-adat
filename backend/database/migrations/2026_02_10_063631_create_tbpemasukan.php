<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbpemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->string('nama_transaksi', 150);

            $table->foreignId('kategori_id')
                ->constrained('kategori_pemasukan')
                ->cascadeOnDelete();

            $table->integer('qty')->default(1);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total', 15, 2);

            $table->string('sumber_dana', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('bukti_pembayaran')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbpemasukan');
    }
};
