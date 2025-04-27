<?php

namespace Joaocoura\Redwood\Handlers;

interface HandlerInterface
{
    public function writeLog(string $message): array;
}