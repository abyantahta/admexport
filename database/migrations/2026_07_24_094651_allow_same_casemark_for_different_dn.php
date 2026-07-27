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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['casemark_no']);
        });

        Schema::table('casemarks', function (Blueprint $table) {
            $table->dropUnique(['casemark_no']);
            $table->unique(['casemark_no', 'dn_no']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign(['casemark_no', 'dn_no'])
                ->references(['casemark_no', 'dn_no'])
                ->on('casemarks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['casemark_no', 'dn_no']);
        });

        Schema::table('casemarks', function (Blueprint $table) {
            $table->dropUnique(['casemark_no', 'dn_no']);
            $table->unique(['casemark_no']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('casemark_no')
                ->references('casemark_no')
                ->on('casemarks')
                ->onDelete('cascade');
        });
    }
};
