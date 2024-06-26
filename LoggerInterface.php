<?php

namespace VBulletin\Log;

interface LoggerInterface
{
    public static function getInstance();
    public function channel(string $name): self;

    public function info(string $message): void;

    public function error(string $message, array $context = []): void;
}
