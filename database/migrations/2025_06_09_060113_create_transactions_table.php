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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kanban_barcode');
            $table->string('part_no_kanban');
            $table->string('part_no_label');
            $table->string('seq_no_kanban');
            $table->string('seq_no_label');
            $table->string('label_barcode');
            $table->enum('status', ['mismatch', 'match']);
            $table->string('casemark_no');
            $table->foreign('casemark_no')->references('casemark_no')->on('casemarks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
