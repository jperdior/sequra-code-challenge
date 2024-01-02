<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementLineRepositoryInterface;
use App\Shared\Infrastructure\Doctrine\AbstractOrmRepository;

class DisbursementLineDoctrineRepository extends AbstractOrmRepository implements DisbursementLineRepositoryInterface
{

    public function getClass(): string
    {
        return DisbursementLine::class;
    }

    public function save(DisbursementLine $disbursementLine): void
    {
        $this->persist($disbursementLine);
    }

    public function findByPurchaseId(string $purchaseId): ?DisbursementLine
    {
        return $this->findOneBy(['purchaseId.value' => $purchaseId]);
    }
}