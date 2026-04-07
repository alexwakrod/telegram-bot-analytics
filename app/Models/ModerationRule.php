<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModerationRule extends Model
{
    protected $fillable = ['rule_type', 'threshold', 'duration_minutes', 'enabled'];
    protected $casts = ['enabled' => 'boolean'];
}