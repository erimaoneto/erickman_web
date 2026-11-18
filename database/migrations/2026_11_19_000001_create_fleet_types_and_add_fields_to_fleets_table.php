<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create fleet_types table
        if (!Schema::hasTable('fleet_types')) {
            Schema::create('fleet_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Seed default types
            $defaultTypes = [
                ['name' => 'CNG Tube Trailer', 'description' => 'Armada trailer pengangkut tabung gas CNG bertekanan tinggi'],
                ['name' => 'Box Truck', 'description' => 'Truk boks logistik distribusi barang ritel dan kargo'],
                ['name' => 'Heavy Box Truck', 'description' => 'Truk boks berat Fuso / Tronton kapasitas besar'],
                ['name' => 'Flatbed Trailer', 'description' => 'Trailer dek datar untuk muatan kontainer dan alat berat'],
                ['name' => 'Tangki Khusus', 'description' => 'Truk tangki pengangkut cairan atau bahan khusus / B3'],
            ];

            foreach ($defaultTypes as $type) {
                DB::table('fleet_types')->insertOrIgnore(array_merge($type, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Also insert any existing distinct types from fleets
        if (Schema::hasTable('fleets')) {
            $existingTypes = DB::table('fleets')->distinct()->pluck('type');
            foreach ($existingTypes as $type) {
                if (!empty($type)) {
                    DB::table('fleet_types')->insertOrIgnore([
                        'name' => $type,
                        'description' => 'Tipe armada operasional terdaftar',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 2. Add columns to fleets table
        Schema::table('fleets', function (Blueprint $table) {
            if (!Schema::hasColumn('fleets', 'order_number')) {
                $table->integer('order_number')->nullable()->default(1)->after('id');
            }
            if (!Schema::hasColumn('fleets', 'stnk_file')) {
                $table->string('stnk_file')->nullable()->after('stnk_expiry');
            }
            if (!Schema::hasColumn('fleets', 'bpkb_file')) {
                $table->string('bpkb_file')->nullable()->after('stnk_file');
            }
            if (!Schema::hasColumn('fleets', 'kir_file')) {
                $table->string('kir_file')->nullable()->after('bpkb_file');
            }
        });

        // Set sequential order_number for existing records
        $fleets = DB::table('fleets')->orderBy('id')->get();
        $idx = 1;
        foreach ($fleets as $f) {
            DB::table('fleets')->where('id', $f->id)->update(['order_number' => $idx++]);
        }
    }

    public function down(): void
    {
        Schema::table('fleets', function (Blueprint $table) {
            if (Schema::hasColumn('fleets', 'order_number')) {
                $table->dropColumn('order_number');
            }
            if (Schema::hasColumn('fleets', 'stnk_file')) {
                $table->dropColumn('stnk_file');
            }
            if (Schema::hasColumn('fleets', 'bpkb_file')) {
                $table->dropColumn('bpkb_file');
            }
            if (Schema::hasColumn('fleets', 'kir_file')) {
                $table->dropColumn('kir_file');
            }
        });

        Schema::dropIfExists('fleet_types');
    }
};
