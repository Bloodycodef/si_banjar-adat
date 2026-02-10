<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['krame', 'klian', 'penyarikan', 'bendahara'])
                ->default('krame');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert default user (password: password123)
        DB::table('users')->insert([
            [
                'name' => 'Admin Bendahara',
                'email' => 'bendahara@example.com',
                'password' => Hash::make('password123'),
                'role' => 'bendahara',
                'phone' => '081234567890',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Klian User',
                'email' => 'klian@example.com',
                'password' => Hash::make('password123'),
                'role' => 'klian',
                'phone' => '081234567891',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Penyarikan User',
                'email' => 'penyarikan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'penyarikan',
                'phone' => '081234567892',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'role' => 'krame',
                'phone' => '081234567893',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
