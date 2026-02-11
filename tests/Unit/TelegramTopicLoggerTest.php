<?php

namespace Minkhantnaung\TelegramTopicLogger\Tests\Unit;

use Illuminate\Support\Facades\Http;
use Minkhantnaung\TelegramTopicLogger\TelegramTopicLogger;
use Minkhantnaung\TelegramTopicLogger\Tests\TestCase;
use Monolog\Level;

final class TelegramTopicLoggerTest extends TestCase
{
    private TelegramTopicLogger $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = new TelegramTopicLogger;
    }

    /** @return array<string, mixed> */
    private function requestBody(\Illuminate\Http\Client\Request $request): array
    {
        $body = $request->body();
        $decoded = json_decode($body, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        $data = [];
        parse_str($body, $data);

        return $data;
    }

    public function test_invoke_returns_monolog_logger_instance(): void
    {
        Http::fake();

        $config = [
            'token' => 'test-bot-token',
            'chat_id' => '-1001234567890',
        ];

        $logger = ($this->logger)($config);

        $this->assertInstanceOf(\Monolog\Logger::class, $logger);
        $this->assertSame('telegram', $logger->getName());
    }

    public function test_logging_sends_message_to_telegram_api(): void
    {
        Http::fake();

        $config = [
            'token' => 'test-bot-token',
            'chat_id' => '-1001234567890',
        ];

        $logger = ($this->logger)($config);
        $logger->error('Test error message');

        Http::assertSent(function ($request) {
            $data = $this->requestBody($request);
            return $request->url() === 'https://api.telegram.org/bottest-bot-token/sendMessage'
                && ($data['chat_id'] ?? null) === '-1001234567890'
                && ($data['text'] ?? null) === 'Test error message';
        });
    }

    public function test_logging_includes_thread_id_when_configured(): void
    {
        Http::fake();

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
            'thread_id' => 456,
        ];

        $logger = ($this->logger)($config);
        $logger->error('Threaded message');

        Http::assertSent(function ($request) {
            $data = $this->requestBody($request);
            return (int) ($data['message_thread_id'] ?? 0) === 456
                && ($data['text'] ?? null) === 'Threaded message';
        });
    }

    public function test_logging_omits_thread_id_when_not_configured(): void
    {
        Http::fake();

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
        ];

        $logger = ($this->logger)($config);
        $logger->error('No thread');

        Http::assertSent(function ($request) {
            $data = $this->requestBody($request);
            return ! array_key_exists('message_thread_id', $data);
        });
    }

    public function test_logging_omits_thread_id_when_empty(): void
    {
        Http::fake();

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
            'thread_id' => null,
        ];

        $logger = ($this->logger)($config);
        $logger->error('Empty thread');

        Http::assertSent(function ($request) {
            $data = $this->requestBody($request);
            return ! array_key_exists('message_thread_id', $data);
        });
    }

    public function test_level_accepts_string_and_filters_logs(): void
    {
        Http::fake();

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
            'level' => 'error',
        ];

        $logger = ($this->logger)($config);
        $logger->debug('Should not be sent');
        $logger->info('Should not be sent');
        $logger->error('Should be sent');

        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            $data = $this->requestBody($request);
            return ($data['text'] ?? null) === 'Should be sent';
        });
    }

    public function test_level_accepts_case_insensitive_string(): void
    {
        Http::fake();

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
            'level' => 'ERROR',
        ];

        $logger = ($this->logger)($config);
        $logger->error('Sent');

        Http::assertSentCount(1);
    }

    public function test_level_accepts_level_enum(): void
    {
        Http::fake();

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
            'level' => Level::Warning,
        ];

        $logger = ($this->logger)($config);
        $logger->info('Not sent');
        $logger->warning('Sent');

        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            $data = $this->requestBody($request);
            return ($data['text'] ?? null) === 'Sent';
        });
    }

    public function test_http_failure_does_not_throw(): void
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
        ];

        $logger = ($this->logger)($config);

        $logger->error('Message');

        $this->assertTrue(true, 'No exception was thrown');
    }

    public function test_connection_exception_does_not_throw(): void
    {
        Http::fake(function () {
            throw new \Exception('Connection failed');
        });

        $config = [
            'token' => 'bot-token',
            'chat_id' => '123',
        ];

        $logger = ($this->logger)($config);

        $logger->error('Message');

        $this->assertTrue(true, 'No exception was thrown');
    }
}
