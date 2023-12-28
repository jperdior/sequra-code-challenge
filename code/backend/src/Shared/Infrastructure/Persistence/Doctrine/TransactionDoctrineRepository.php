<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Repository\TransactionInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class TransactionDoctrineRepository implements TransactionInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function open(): void
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->em->getConnection()->commit();
    }

    public function clear(): void
    {
        $this->em->clear();
    }

    public function rollback(): void
    {
        $this->em->getConnection()->rollBack();
    }
}
