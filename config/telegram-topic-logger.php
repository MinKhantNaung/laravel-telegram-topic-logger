<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default configuration values for the Telegram Topic Logger.
    | You can override these in your logging.php config file.
    |
    */

    'default_chat_id' => env('TELEGRAM_CHAT_ID'),
    'default_token' => env('TELEGRAM_BOT_TOKEN'),
    'default_thread_id' => env('TELEGRAM_THREAD_ID'),
    'default_level' => env('TELEGRAM_LOG_LEVEL', 'debug'),
];

