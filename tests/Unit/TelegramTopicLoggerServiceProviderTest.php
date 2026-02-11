<?php

namespace Minkhantnaung\TelegramTopicLogger\Tests\Unit;

use Minkhantnaung\TelegramTopicLogger\Tests\TestCase;

final class TelegramTopicLoggerServiceProviderTest extends TestCase
{
    public function test_telegram_topic_logger_config_is_merged(): void
    {
        $config = config('telegram-topic-logger');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('default_chat_id', $config);
        $this->assertArrayHasKey('default_token', $config);
        $this->assertArrayHasKey('default_thread_id', $config);
        $this->assertArrayHasKey('default_level', $config);
    }

    public function test_provider_is_registered(): void
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(\Minkhantnaung\TelegramTopicLogger\TelegramTopicLoggerServiceProvider::class)
        );
    }
}
