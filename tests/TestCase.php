<?php

namespace Minkhantnaung\TelegramTopicLogger\Tests;

use Minkhantnaung\TelegramTopicLogger\TelegramTopicLoggerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TelegramTopicLoggerServiceProvider::class,
        ];
    }
}
