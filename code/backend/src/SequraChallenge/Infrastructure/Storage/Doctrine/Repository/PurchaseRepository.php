<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;

class PurchaseRepository extends AbstractOrmRepository implements PurchaseRepositoryInterface
{
    protected function getClass(): string
    {
        return Purchase::class;
    }

    public function getNotProcessed(int $limit, int $offset): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from(Purchase::class, 'p')
            ->leftJoin(DisbursementLine::class, 'dl', 'WITH', 'p.id = dl.purchase')
            ->where('dl.purchase IS NULL')
            ->orderBy('p.createdAt', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function save(Purchase $purchase): void
    {
        $this->getEntityManager()->persist($purchase);
        $this->getEntityManager()->flush();
    }

    public function findById(string $id): ?Purchase
    {
        return $this->find($id);
    }
}
