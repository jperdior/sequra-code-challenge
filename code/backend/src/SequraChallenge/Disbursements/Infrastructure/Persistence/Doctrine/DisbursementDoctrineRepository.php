<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementMerchantReference;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\Shared\Infrastructure\Doctrine\AbstractOrmRepository;

class DisbursementDoctrineRepository extends AbstractOrmRepository implements DisbursementRepositoryInterface
{
    protected function getClass(): string
    {
        return Disbursement::class;
    }

    public function getByMerchantAndDisbursedDate(
        DisbursementMerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): ?Disbursement {
        return $this->findOneBy([
            'merchantReference' => $merchantReference,
            'disbursedAt' => $disbursedAt
        ]);
    }

    public function save(Disbursement $disbursement): void
    {
        $this->persist($disbursement);
    }
}
