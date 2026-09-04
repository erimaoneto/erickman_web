<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. TRX-2026-001
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->string('category'); // BBM & Gas, Uang Jalan Supir, Sewa Truk, Distribusi CNG, Maintenance, Tol & Operasional, Lainnya
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->foreignId('fleet_id')->nullable()->constrained('fleets')->nullOnDelete();
            $table->string('reference_invoice')->nullable();
            $table->text('description')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
