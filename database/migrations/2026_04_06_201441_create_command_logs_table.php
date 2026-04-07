<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('command_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('command', 50);
            $table->timestamp('timestamp')->useCurrent();
            $table->index(['user_id', 'timestamp']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('command_logs');
    }
};