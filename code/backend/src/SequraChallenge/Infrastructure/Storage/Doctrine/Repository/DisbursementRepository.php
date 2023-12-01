<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Repository\DisbursementRepositoryInterface;

class DisbursementRepository extends AbstractOrmRepository implements DisbursementRepositoryInterface
{
    protected function getClass(): string
    {
        return Disbursement::class;
    }

    public function getByMerchantAndDisbursedDate(Merchant $merchant, \DateTime $createdAt): ?Disbursement
    {
        $qb = $this->createQueryBuilder('d');
        $qb->where('d.merchant = :merchant')
            ->andWhere('d.disbursedAt = :createdAt')
            ->setParameter('merchant', $merchant)
            ->setParameter('createdAt', $createdAt->format('Y-m-d'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getFirstOfMonth(Merchant $merchant, \DateTime $dateTime): ?Disbursement
    {
        $qb = $this->createQueryBuilder('d');
        $qb->where('d.merchant = :merchant')
            ->andWhere('d.createdAt = :createdAt')
            ->setParameter('merchant', $merchant)
            ->setParameter('createdAt', $dateTime);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getSumOfLastMonthFees(Merchant $merchant, \DateTime $date): float
    {
        $qb = $this->createQueryBuilder('d');
        $qb->select('SUM(d.fees) as fees')
            ->where('d.merchant = :merchant')
            ->andWhere('d.createdAt >= :createdAt')
            ->setParameter('merchant', $merchant)
            ->setParameter('createdAt', $date);

        return (float) $qb->getQuery()->getSingleScalarResult();
    }

    public function save(Disbursement $disbursement): void
    {
        $this->getEntityManager()->persist($disbursement);
        $this->getEntityManager()->flush();
    }
}
