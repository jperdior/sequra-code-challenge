<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\CancellationLine;
use App\SequraChallenge\Domain\Repository\CancellationLineRepositoryInterface;

class CancellationLineRepository extends AbstractOrmRepository implements CancellationLineRepositoryInterface
{
    protected function getClass(): string
    {
        return CancellationLine::class;
    }

    public function save(CancellationLine $cancellationLine): void
    {
        $this->getEntityManager()->persist($cancellationLine);
        $this->getEntityManager()->flush();
    }

    public function sumAmountByDisbursementLineId(string $disbursementLineId): float
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sum(cl.amount)')
            ->from(CancellationLine::class, 'cl')
            ->where('cl.disbursementLine = :disbursementLineId')
            ->setParameter('disbursementLineId', $disbursementLineId);

        return $qb->getQuery()->getSingleScalarResult() ?? 0;
    }
}
