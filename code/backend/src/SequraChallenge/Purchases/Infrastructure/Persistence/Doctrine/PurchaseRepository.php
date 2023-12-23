<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Purchases\Domain\Purchase;
use App\SequraChallenge\Purchases\Domain\PurchaseRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\AbstractOrmRepository;

class PurchaseRepository extends AbstractOrmRepository implements PurchaseRepositoryInterface
{
    protected function getClass(): string
    {
        return Purchase::class;
    }

    public function getNotProcessed(int $limit): array
    {
        // p.status === Purchase::STATUS_PENDING
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from(Purchase::class, 'p')
            ->where('p.status = 0')
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

    public function setStatus(array $purchaseIds, int $status)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->update(Purchase::class, 'p')
            ->set('p.status', $status)
            ->where('p.id IN (:purchaseIds)')
            ->setParameter('purchaseIds', $purchaseIds);

        $qb->getQuery()->execute();
    }

}
