<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;

class DisbursementLineRepository extends AbstractOrmRepository implements DisbursementLineRepositoryInterface
{
    protected function getClass(): string
    {
        return DisbursementLine::class;
    }

    public function save(DisbursementLine $disbursementLine): void
    {
        $this->getEntityManager()->persist($disbursementLine);
        $this->getEntityManager()->flush();
    }

    public function existsByPurchase(string $purchaseId): bool
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(dl.id)')
            ->from(DisbursementLine::class, 'dl')
            ->where('dl.purchaseId = :purchaseId')
            ->setParameter('purchaseId', $purchaseId);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
