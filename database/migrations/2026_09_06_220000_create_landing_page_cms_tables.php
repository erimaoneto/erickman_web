<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('key_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('number_value');
            $table->string('label');
            $table->string('color_theme')->default('brand'); // brand, navy, emerald, sky
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_pillars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('fa-solid fa-fire-flame-simple');
            $table->string('color_theme')->default('orange'); // orange, blue, emerald, brand
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hse_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('fa-solid fa-shield-halved');
            $table->string('color_theme')->default('brand'); // brand, emerald, sky, red
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->string('icon')->nullable()->default('fa-solid fa-building');
            $table->string('color_theme')->default('blue'); // orange, red, sky, blue, emerald, amber
            $table->string('logo_path')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('hse_items');
        Schema::dropIfExists('about_pillars');
        Schema::dropIfExists('key_metrics');
        Schema::dropIfExists('nav_menus');
    }
};
