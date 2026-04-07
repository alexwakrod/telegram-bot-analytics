<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandLog extends Model
{
    public $timestamps = false;
    protected $table = 'command_logs';
    protected $fillable = ['user_id', 'command', 'timestamp'];
    protected $casts = ['timestamp' => 'datetime'];
}