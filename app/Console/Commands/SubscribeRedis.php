<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Events\BotEvent;
use Throwable;

class SubscribeRedis extends Command
{
    protected $signature = 'redis:subscribe';
    protected $description = 'Subscribe to Redis channel bot_events and broadcast via WebSocket (auto‑reconnect)';

    public function handle()
    {
        $this->info('Listening for Redis messages on channel bot_events (predis, infinite timeout)...');

        while (true) {
            try {
                Redis::connection('subscriber')->subscribe(['bot_events'], function ($message) {
                    $this->info('Received: ' . $message);
                    broadcast(new BotEvent(json_decode($message, true)));
                });
            } catch (Throwable $e) {
                $this->error('Redis subscription error: ' . $e->getMessage());
                $this->info('Reconnecting in 5 seconds...');
                sleep(5);
            }
        }
    }
}