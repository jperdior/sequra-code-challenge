<?php

declare(strict_types=1);

namespace App\Shared\Domain\Lock;

interface LockingInterface
{
    public function create(string $resource): void;

    public function acquire(bool $blocking = false): bool;

    public function release(): void;
}
