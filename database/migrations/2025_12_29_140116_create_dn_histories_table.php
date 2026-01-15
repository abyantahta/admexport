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
        Schema::create('dn_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('dn_no');
            $table->foreign('dn_no')->references('dn_no')->on('dns')->onDelete('cascade');
            $table->string('pic');
            $table->text('remarks')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dn_histories');
    }
};
