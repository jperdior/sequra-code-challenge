<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;

class PurchaseRepository extends AbstractOrmRepository implements PurchaseRepositoryInterface
{

    protected function getClass(): string
    {
        return Purchase::class;
    }

    public function getOldestPendingPurchase()
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.status = :status')
            ->setParameter('status', PurchaseStatusEnum::PENDING->value)
            ->orderBy('p.createdAt', 'ASC')
            ->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function save(Purchase $purchase): void
    {
        $this->getEntityManager()->persist($purchase);
        $this->getEntityManager()->flush();
    }
}