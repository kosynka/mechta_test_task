<?php

namespace VBulletin\Log;

use Exception;

class Log implements LoggerInterface
{
    private array $channels = [
        'default' => [
            'root' => '/var/www/',
            'file_name' => 'log.txt',
        ],
        'search' => [
            'root' => '/var/www/',
            'file_name' => 'search_log.txt',
        ],
    ];

    public function __construct(private string $channel = 'default')
    {
        $this->channel = $this->channels[$channel];
    }

    public function channel(string $name): self
    {
        if (!array_key_exists($name, $this->channels)) {
            throw new Exception("Channel {$name} is not defined.");
        }

        $this->channel = $name;

        return $this;
    }

    public function info(string $message): void
    {
        try {
            $file = fopen($this->getFilePath(), 'a');

            if ($file) {
                fwrite($file, $message . "\n");
                fclose($file);
            } else {
                throw new Exception("Failed to open log file for writing.");
            }
        } catch (Exception $e) {
            echo "Error logging message: " . $e->getMessage();
        }
    }

    public function error(string $message, array $context = []): void
    {
        try {
            $file = fopen($this->getFilePath(), 'a');

            if ($file) {
                fwrite($file, "ERROR: " . $message . "\n");
                fwrite($file, "CONTEXT: " . json_encode($context) . "\n");
                fclose($file);
            } else {
                throw new Exception("Failed to open log file for writing.");
            }
        } catch (Exception $e) {
            echo "Error logging message: " . $e->getMessage();
        }
    }

    private function getFilePath(): string
    {
        $root = $this->channels[$this->channel]['root'];
        $file = $this->channels[$this->channel]['file_name'];

        return $root . $file;
    }
}
