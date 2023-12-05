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

    public function getNotProcessed(int $limit): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from(Purchase::class, 'p')
            ->where('p.processed = false')
            ->setMaxResults($limit);

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

    public function markAsProcessed($purchaseIds): void
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->update(Purchase::class, 'p')
            ->set('p.processed', true)
            ->where('p.id IN (:purchaseIds)')
            ->setParameter('purchaseIds', $purchaseIds);

        $qb->getQuery()->execute();
    }

}
