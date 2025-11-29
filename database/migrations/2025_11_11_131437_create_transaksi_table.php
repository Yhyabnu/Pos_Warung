<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_pelanggan')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('pajak', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->decimal('uang_dibayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'qris', 'transfer'])->default('tunai');
            $table->enum('status', ['selesai', 'pending', 'dibatalkan'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};