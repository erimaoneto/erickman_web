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
        Schema::table('gtm_modules', function (Blueprint $table) {
            $table->string('location', 150)->nullable()->after('fleet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtm_modules', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
