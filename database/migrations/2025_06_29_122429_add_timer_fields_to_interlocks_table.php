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
        Schema::table('interlocks', function (Blueprint $table) {
            $table->boolean('notification_30m_sent')->default(false);
            $table->boolean('notification_60m_sent')->default(false);
            $table->timestamp('notification_30m_sent_at')->nullable();
            $table->timestamp('notification_60m_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interlocks', function (Blueprint $table) {
            $table->dropColumn(['notification_30m_sent', 'notification_60m_sent', 'notification_30m_sent_at', 'notification_60m_sent_at']);
        });
    }
};
