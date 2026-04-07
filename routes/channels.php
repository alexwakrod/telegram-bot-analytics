<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('bot-events', function ($user) {
    return true; // Public channel, no auth needed
});