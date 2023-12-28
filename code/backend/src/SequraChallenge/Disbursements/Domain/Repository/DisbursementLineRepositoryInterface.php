<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Repository;

use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementLine;

interface DisbursementLineRepositoryInterface
{
    public function save(DisbursementLine $disbursementLine): void;
}
