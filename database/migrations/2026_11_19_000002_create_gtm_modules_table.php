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
        Schema::create('gtm_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('order_number')->nullable()->default(1);
            $table->string('tube_code', 100)->unique();
            $table->string('tube_brand', 100);
            $table->string('capacity', 100);
            $table->string('manufacture_year', 10);
            $table->foreignId('fleet_id')->nullable()->constrained('fleets')->nullOnDelete();
            $table->date('hydrotest_expiry')->nullable();
            $table->string('status', 50)->default('aktif'); // aktif, standby, rekualifikasi, perbaikan
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtm_modules');
    }
};
