<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Purchase;
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

    public function getAmountSumByDisbursement(Disbursement $disbursement): float
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sum(dl.amount)')
            ->from(DisbursementLine::class, 'dl')
            ->where('dl.disbursement = :disbursement')
            ->setParameter('disbursement', $disbursement);

        return $qb->getQuery()->getSingleScalarResult() ?? 0;
    }

    public function getFeeAmountSumByDisbursement(Disbursement $disbursement): float
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sum(dl.feeAmount)')
            ->from(DisbursementLine::class, 'dl')
            ->where('dl.disbursement = :disbursement')
            ->setParameter('disbursement', $disbursement);

        return $qb->getQuery()->getSingleScalarResult() ?? 0;
    }

    public function findByPurchase(Purchase $purchase): ?DisbursementLine
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('dl')
            ->from(DisbursementLine::class, 'dl')
            ->where('dl.purchase = :purchase')
            ->setParameter('purchase', $purchase);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
