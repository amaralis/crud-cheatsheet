<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('band_id');
            $table->foreign('band_id')->references('id')->on('bands');
            $table->string('cover_image', length:500)->nullable()->default('default_album.jpg');
            $table->string('name');
            $table->date('launch_date')->default(now());
            $table->uuid('uuid')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
