<?php

namespace Joaocoura\Redwood\Loggers;

use Joaocoura\Redwood\Handlers\FileHandler;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * This class is responsible for logging messages to a file.
 */
class FileLogger implements LoggerInterface 
{
    private FileHandler $fileHandler;
    private array $allowedLevels;

    public function __construct(FileHandler $fileHandler, array $allowedLevels)
    {
        $this->fileHandler = $fileHandler;
        $this->allowedLevels = $allowedLevels;
    }

    public function emergency($message, array $context = []): void
    {
        if (!in_array(LogLevel::EMERGENCY, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        if (!in_array(LogLevel::ALERT, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        if (!in_array(LogLevel::CRITICAL, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        if (!in_array(LogLevel::ERROR, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        if (!in_array(LogLevel::WARNING, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        if (!in_array(LogLevel::NOTICE, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        if (!in_array(LogLevel::INFO, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        if (!in_array(LogLevel::DEBUG, $this->allowedLevels)) {
            return;
        }

        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        if (!in_array($level, $this->allowedLevels)) {
            return;
        }

        $payload = [
            'timestamp' => time(),
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];

        $this->fileHandler->writeLog(json_encode($payload));
    }
}