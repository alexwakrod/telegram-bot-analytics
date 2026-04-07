<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('initiator_id');
            $table->unsignedBigInteger('acceptor_id')->nullable();
            $table->string('status', 20)->default('pending');
            $table->json('data');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};