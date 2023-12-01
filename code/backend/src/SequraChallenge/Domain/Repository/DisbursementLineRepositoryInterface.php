<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

interface DisbursementLineRepositoryInterface
{
    public function save(\App\SequraChallenge\Domain\Entity\DisbursementLine $disbursementLine);
}