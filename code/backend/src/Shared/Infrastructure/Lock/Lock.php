<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Lock;

use App\Shared\Domain\Lock\LockingInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Lock\Store\FlockStore;

final class Lock implements LockingInterface
{
    private LockFactory $lockFactory;

    private LockInterface $lock;

    public function __construct()
    {
        $this->lockFactory = new LockFactory(new FlockStore());
    }

    public function create(string $resource): void
    {
        $this->lock = $this->lockFactory->createLock($resource);
    }

    public function acquire(bool $blocking = false): bool
    {
        return $this->lock->acquire($blocking);
    }

    public function release(): void
    {
        $this->lock->release();
    }
}
