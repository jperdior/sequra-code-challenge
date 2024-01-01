<?php

declare(strict_types=1);

namespace App\Shared\Domain\Logging;

interface LoggingInterface
{
    public function info(string $message): void;

    public function error(string $message): void;

    public function warning(string $message): void;
}
