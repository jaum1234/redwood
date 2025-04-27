<?php

use Joaocoura\Redwood\Handlers\FileHandler;
use Joaocoura\Redwood\Loggers\FileLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class FileLoggerTest extends TestCase
{
    private FileHandler $fileHandlerMock;
    private FileLogger $fileLogger;

    protected function setUp(): void
    {
        $this->fileHandlerMock = $this->createMock(FileHandler::class);
        $this->fileLogger = new FileLogger($this->fileHandlerMock, [
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
        ]);
    }

    public function testEmergencyLogsMessageWhenLevelAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->once())
            ->method('writeLog')
            ->with($this->callback(function ($log) {
                $data = json_decode($log, true);
                return $data['level'] === LogLevel::EMERGENCY && $data['message'] === 'Test emergency';
            }));

        $this->fileLogger->emergency('Test emergency');
    }

    public function testInfoLogsMessageWhenLevelAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->once())
            ->method('writeLog')
            ->with($this->callback(function ($log) {
                $data = json_decode($log, true);
                return $data['level'] === LogLevel::INFO && $data['message'] === 'Test info';
            }));

        $this->fileLogger->info('Test info');
    }

    public function testDebugDoesNotLogMessageWhenLevelNotAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->never())
            ->method('writeLog');

        $this->fileLogger->debug('Test debug');
    }

    public function testLogWritesMessageWhenLevelAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->once())
            ->method('writeLog')
            ->with($this->callback(function ($log) {
                $data = json_decode($log, true);
                return $data['level'] === LogLevel::ERROR && $data['message'] === 'Test error';
            }));

        $this->fileLogger->log(LogLevel::ERROR, 'Test error');
    }

    public function testAlertLogsMessageWhenLevelAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->never())
            ->method('writeLog');

        $this->fileLogger->alert('Test alert');
    }

    public function testCriticalLogsMessageWhenLevelAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->never())
            ->method('writeLog');

        $this->fileLogger->critical('Test critical');
    }

    public function testErrorLogsMessageWhenLevelAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->once())
            ->method('writeLog')
            ->with($this->callback(function ($log) {
                $data = json_decode($log, true);
                return $data['level'] === LogLevel::ERROR && $data['message'] === 'Test error';
            }));

        $this->fileLogger->error('Test error');
    }

    public function testWarningDoesNotLogMessageWhenLevelNotAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->never())
            ->method('writeLog');

        $this->fileLogger->warning('Test warning');
    }

    public function testNoticeDoesNotLogMessageWhenLevelNotAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->never())
            ->method('writeLog');

        $this->fileLogger->notice('Test notice');
    }

    public function testLogDoesNotWriteMessageWhenLevelNotAllowed(): void
    {
        $this->fileHandlerMock
            ->expects($this->never())
            ->method('writeLog');

        $this->fileLogger->log(LogLevel::WARNING, 'Test warning');
    }


}