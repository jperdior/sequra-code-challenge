<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementLineRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\AbstractOrmRepository;

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
}