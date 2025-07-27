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
        Schema::create('resep_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_id')->constrained('reseps')->onDelete('cascade');
            $table->string('nama_resep')->nullable();
            $table->integer('obatalkes_id');
            $table->foreign('obatalkes_id')->references('obatalkes_id')->on('obatalkes_m')->onDelete('cascade');
            $table->integer('signa_id');
            $table->foreign('signa_id')->references('signa_id')->on('signa_m')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};