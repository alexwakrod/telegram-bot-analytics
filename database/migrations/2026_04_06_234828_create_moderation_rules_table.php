<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('moderation_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('rule_type', ['warn', 'mute', 'ban']);
            $table->integer('threshold');
            $table->integer('duration_minutes')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('moderation_rules');
    }
};