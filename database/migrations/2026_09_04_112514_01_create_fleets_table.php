<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique(); // e.g. B 1234 ABC, T 8434 DY
            $table->string('vehicle_name'); // e.g. UD Quester CNG Trailer 01, Toyota Dyna Box 02
            $table->string('type'); // CNG Tube Trailer, Box Truck, Flatbed Trailer, etc.
            $table->string('brand')->nullable(); // UD Trucks, Toyota, Hino, Isuzu
            $table->string('year')->nullable(); // 2022
            $table->string('capacity')->nullable(); // e.g. 2000 m3 CNG, 8 Ton Box
            $table->enum('status', ['Tersedia', 'Dalam Perjalanan', 'Perawatan', 'Non-Aktif'])->default('Tersedia');
            $table->string('driver_name')->nullable();
            $table->date('kir_expiry')->nullable();
            $table->date('stnk_expiry')->nullable();
            $table->string('image_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
