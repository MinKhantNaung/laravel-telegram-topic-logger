# Changelog

All notable changes to `laravel-telegram-topic-logger` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-06-24

### Added

- Support for **Laravel 13** (`illuminate/support: ^13.0`).
- CI test matrix covering Laravel 11, 12, and 13 across PHP 8.2–8.4.

### Changed

- Widened `orchestra/testbench` constraint to `^9.0|^10.0|^11.0` and `phpunit/phpunit` to `^11.0|^12.0` to test against the supported Laravel versions.

> **Note:** Laravel 13 requires PHP 8.3+. Laravel 11 and 12 continue to work on PHP 8.2+.

## [1.0.0]

### Added

- Initial release: a custom Laravel logging channel that sends logs to Telegram with topic/thread (`message_thread_id`) support, built on Monolog v3.

[1.1.0]: https://github.com/MinKhantNaung/laravel-telegram-topic-logger/releases/tag/v1.1.0
[1.0.0]: https://github.com/MinKhantNaung/laravel-telegram-topic-logger/releases/tag/v1.0.0
