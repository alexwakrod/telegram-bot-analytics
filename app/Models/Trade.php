<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = ['initiator_id', 'acceptor_id', 'status', 'data'];
    protected $casts = ['data' => 'array'];
}