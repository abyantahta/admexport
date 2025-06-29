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
            $table->integer('cycle');
            $table->string('truck_no');
            $table->integer('week');
            $table->date('order_date');
            $table->integer('periode');
            $table->integer('etd');
            $table->integer('qty_casemark')->default(1);
            $table->integer('count_casemark')->default(0);
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
