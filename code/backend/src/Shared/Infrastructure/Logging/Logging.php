<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Logging;

use App\Shared\Domain\Logging\LoggingInterface;
use Psr\Log\LoggerInterface;

class Logging implements LoggingInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function error(string $message): void
    {
        $this->logger->error($message);
    }

    public function info(string $message): void
    {
        $this->logger->info($message);
    }

    public function warning(string $message): void
    {
        $this->logger->warning($message);
    }
}
