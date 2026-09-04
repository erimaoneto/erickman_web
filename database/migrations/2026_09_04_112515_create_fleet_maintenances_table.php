<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleet_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->date('service_date');
            $table->string('service_type'); // Ganti Oli, Uji KIR, Ganti Ban, Kalibrasi Silinder CNG, dll.
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('workshop')->nullable(); // Nama bengkel / fasilitas servis
            $table->integer('odometer_km')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_maintenances');
    }
};
