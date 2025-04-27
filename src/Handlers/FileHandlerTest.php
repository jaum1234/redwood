<?php

use Joaocoura\Redwood\Handlers\FileHandler;
use PHPUnit\Framework\TestCase;

final class FileHandlerTest extends TestCase {
    public function testShouldWriteLogToFile() {
        // Arrange
        $filePath = __DIR__ . '/test.log';
        $handler = new FileHandler($filePath);
        
        $message = 'Test log message';

        // Act
        list (, $err) = $handler->writeLog($message);
        
        // Assert
        $this->assertEquals(null, $err);
        $this->assertFileExists($filePath);
        $this->assertStringContainsString($message, file_get_contents($filePath));
        
        unlink($filePath);
    }

    public function testShouldRotateLogFile() {
        // Arrange
        $filePath = __DIR__ . '/test.log';
        $handler = new FileHandler($filePath);
        
        $message = 'Test log message';
        $handler->writeLog($message . ' 1');

        $theDayBefore = strtotime('-1 day');
        $fileDate = date('Y-m-d', $theDayBefore);

        touch($filePath, $theDayBefore);

        // Act
        $handler->writeLog($message . ' 2');

        // Assert
        $this->assertStringContainsString($message . ' 1', file_get_contents($filePath . '.' . $fileDate));
        $this->assertStringContainsString($message . ' 2', file_get_contents($filePath));
        $this->assertFileExists($filePath . '.' . $fileDate);
        
        unlink($filePath);
        unlink($filePath . '.' . $fileDate);
    }

    public function testShouldReturnErrorWhenFileCannotBeOpened() {
        // Arrange
        $filePath = '/invalid/path/test.log';
        $handler = new FileHandler($filePath);
        
        $message = 'Test log message';

        // Act
        list (, $err) = $handler->writeLog($message);
        
        // Assert
        $this->assertNotEquals(null, $err);
        $this->assertStringContainsString('Failed to open log file', $err);
    }
}