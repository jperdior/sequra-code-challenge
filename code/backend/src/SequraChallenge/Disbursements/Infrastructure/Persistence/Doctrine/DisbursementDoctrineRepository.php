<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Disbursements\Domain\DisbursementRepositoryInterface;
use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Infrastructure\Doctrine\AbstractOrmRepository;

class DisbursementDoctrineRepository extends AbstractOrmRepository implements DisbursementRepositoryInterface
{
    public function getByMerchantAndDisbursedDate(
        MerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): ?Disbursement {
        return $this->findOneBy([
            'merchantReference' => $merchantReference,
            'disbursedAt.value' => $disbursedAt->value,
        ]);
    }

    public function getFirstOfMonth(
        MerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): ?Disbursement {
        $monthStart = $disbursedAt->value->format('Y-m-01');
        $monthEnd = $disbursedAt->value->format('Y-m-t');
        $qb = $this->createQueryBuilder('d');
        $qb->select('d')
            ->where('d.merchantReference = :merchantReference')
            ->andWhere('d.disbursedAt.value BETWEEN :monthStart AND :monthEnd')
            ->andWhere('d.firstOfMonth = true')
            ->setParameter('merchantReference', $merchantReference)
            ->setParameter('monthStart', $monthStart)
            ->setParameter('monthEnd', $monthEnd);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getMerchantMonthDisbursementFeesSum(MerchantReference $merchantReference, \DateTime $firstDayOfMonth): float
    {
        $monthStart = $firstDayOfMonth->format('Y-m-01');
        $monthEnd = $firstDayOfMonth->format('Y-m-t');
        $qb = $this->createQueryBuilder('d');
        $qb->select('SUM(d.fee.value)')
            ->where('d.merchantReference = :merchantReference')
            ->andWhere('d.disbursedAt.value BETWEEN :monthStart AND :monthEnd')
            ->setParameter('merchantReference', $merchantReference)
            ->setParameter('monthStart', $monthStart)
            ->setParameter('monthEnd', $monthEnd);

        return (float) $qb->getQuery()->getSingleScalarResult();
    }

    public function save(Disbursement $disbursement): void
    {
        $this->persist($disbursement);
    }

    protected function getClass(): string
    {
        return Disbursement::class;
    }
}
