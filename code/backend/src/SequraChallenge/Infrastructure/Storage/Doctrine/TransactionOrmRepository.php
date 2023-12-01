<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine;

use App\Sequrachallenge\Domain\Repository\TransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class TransactionOrmRepository implements TransactionRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    public function open()
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->em->getConnection()->commit();
    }

    public function clear()
    {
        $this->em->clear();
    }

    public function rollback()
    {
        $this->em->getConnection()->rollBack();
    }
}
