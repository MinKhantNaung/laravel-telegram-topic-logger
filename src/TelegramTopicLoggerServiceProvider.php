<?php

namespace Minkhantnaung\TelegramTopicLogger;

use Illuminate\Support\ServiceProvider;

class TelegramTopicLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/telegram-topic-logger.php' => config_path('telegram-topic-logger.php'),
            ], 'telegram-topic-logger-config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/telegram-topic-logger.php',
            'telegram-topic-logger'
        );
    }
}

