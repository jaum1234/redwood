<?php

namespace Joaocoura\Redwood\Handlers;

class FileHandler implements HandlerInterface
{
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function writeLog(string $message): array
    {
        $this->rotateIfNeeded();

        $file = fopen($this->filePath, 'a');

        if ($file === false) {
            return [null, "Failed to open log file: {$this->filePath}"];
        }

        if ($file) {
            try {
                flock($file, LOCK_EX);
                fwrite($file, $message . PHP_EOL);
                flock($file, LOCK_UN);
                fclose($file);
            } catch (\Exception $e) {
                return [null, $e->getMessage()];
            }
        }

        return [null, null];
    }

    private function rotateIfNeeded(): void
    {
        $currentDate = date('Y-m-d');

        if (file_exists($this->filePath)) {
            $fileDate = date('Y-m-d', filemtime($this->filePath));
            if ($fileDate !== $currentDate) {
                rename($this->filePath, $this->filePath . '.'. $fileDate);
            }
        }
    }

}