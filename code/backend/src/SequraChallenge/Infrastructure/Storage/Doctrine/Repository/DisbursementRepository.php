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

    public function getByMerchantAndDisbursedDate(Merchant $merchant, \DateTime $disbursementDate): ?Disbursement
    {
        $qb = $this->createQueryBuilder('d');
        $qb->where('d.merchant = :merchant')
            ->andWhere('d.disbursedAt = :createdAt')
            ->setParameter('merchant', $merchant)
            ->setParameter('createdAt', $disbursementDate->format('Y-m-d'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function countDisbursementsOfMonth(Merchant $merchant, \DateTime $dateTime): int
    {
        $firstDayOfMonth = clone $dateTime;
        $firstDayOfMonth->modify('first day of this month');
        $firstDayOfMonth->setTime(0, 0, 0);

        $lastDayOfMonth = clone $dateTime;
        $lastDayOfMonth->modify('last day of this month');
        $lastDayOfMonth->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('d');
        $qb->select('COUNT(d.id) as disbursements')
            ->where('d.merchant = :merchant')
            ->andWhere($qb->expr()->between('d.disbursedAt', ':startDate', ':endDate'))
            ->setParameter('merchant', $merchant)
            ->setParameter('startDate', $firstDayOfMonth)
            ->setParameter('endDate', $lastDayOfMonth);

        return (int) $qb->getQuery()->getSingleScalarResult();

    }

    public function getSumOfLastMonthFees(Merchant $merchant, \DateTime $date): float
    {
        $firstDayOfPreviousMonth = clone $date;
        $firstDayOfPreviousMonth->modify('first day of previous month');
        $firstDayOfPreviousMonth->setTime(0, 0, 0);

        $lastDayOfPreviousMonth = clone $date;
        $lastDayOfPreviousMonth->modify('last day of previous month');
        $lastDayOfPreviousMonth->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('d');
        $qb->select('SUM(d.fees) as fees')
            ->where('d.merchant = :merchant')
            ->andWhere($qb->expr()->between('d.disbursedAt', ':startDate', ':endDate'))
            ->setParameter('merchant', $merchant)
            ->setParameter('startDate', $firstDayOfPreviousMonth)
            ->setParameter('endDate', $lastDayOfPreviousMonth);

        return (float) $qb->getQuery()->getSingleScalarResult() ?? 0;
    }

    public function save(Disbursement $disbursement): void
    {
        $this->getEntityManager()->persist($disbursement);
        $this->getEntityManager()->flush();
    }
}
