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
        Schema::create('casemarks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('casemark_no')->unique();
            $table->string('part_no');
            $table->string('part_name');
            $table->string('box_type');
            $table->integer('qty_per_box');
            $table->integer('qty_box');
            $table->integer('count_box');
            $table->boolean('isMatched');
            $table->string('dn_no')->unique();
            $table->foreign('dn_no')->references('dn_no')->on('dns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casemarks');
    }
};
