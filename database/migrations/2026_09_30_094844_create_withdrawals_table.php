<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Menghubungkan ke ID Admin Toko
            $table->decimal('amount', 15, 2);                                // Nominal penarikan
            $table->string('bank_name');                                     // Nama Bank (BCA, Mandiri, dll)
            $table->string('account_number');                                // Nomor Rekening
            $table->string('account_holder');                                // Nama Pemilik Rekening
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING'); // Status pengajuan
            $table->text('note')->nullable();                                // Alasan jika ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};