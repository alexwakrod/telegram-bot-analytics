<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action', 50);
            $table->text('reason');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('moderation_logs');
    }
};