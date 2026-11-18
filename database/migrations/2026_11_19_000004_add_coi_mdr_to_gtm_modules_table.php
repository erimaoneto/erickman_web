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
            $table->string('coi_mdr', 150)->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtm_modules', function (Blueprint $table) {
            $table->dropColumn('coi_mdr');
        });
    }
};
