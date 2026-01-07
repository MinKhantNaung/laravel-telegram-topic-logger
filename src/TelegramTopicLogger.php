<?php

namespace Minkhantnaung\TelegramTopicLogger;

use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;

class TelegramTopicLogger
{
    public function __invoke(array $config): Logger
    {
        return new Logger('telegram', [
            new class($config) extends AbstractProcessingHandler
            {
                protected array $config;

                public function __construct(array $config)
                {
                    $level = $config['level'] ?? Level::Error;
                    
                    // Convert string level to Level enum if needed
                    if (is_string($level)) {
                        $level = Level::fromName(strtoupper($level));
                    }
                    
                    parent::__construct($level);
                    $this->config = $config;
                }

                protected function write(LogRecord $record): void
                {
                    $payload = [
                        'chat_id' => $this->config['chat_id'],
                        'text' => (string) $record->message,
                    ];

                    if (! empty($this->config['thread_id'])) {
                        $payload['message_thread_id'] = $this->config['thread_id'];
                    }

                    try {
                        Http::post(
                            "https://api.telegram.org/bot{$this->config['token']}/sendMessage",
                            $payload
                        );
                    } catch (\Throwable $e) {
                        // Silently fail - never break production
                    }
                }
            },
        ]);
    }
}

