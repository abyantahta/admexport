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
        Schema::create('interlocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('isLocked')->default(false);
            $table->string('part_no_fg')->nullable()->default(null);
            $table->string('part_no_kanban')->nullable()->default(null);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interlocks');
    }
};
