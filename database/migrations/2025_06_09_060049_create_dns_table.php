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
        Schema::create('dns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('dn_no')->unique();
            $table->string('part_no');
            $table->string('truck_no');
            $table->integer('week');
            $table->date('order_Date');
            $table->integer('dn_seq');
            $table->date('periode');
            $table->date('etd');
            $table->integer('qty_casemark');
            $table->integer('count_casemark');
            $table->boolean('isMatch')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dns');
    }
};
