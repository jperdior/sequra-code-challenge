<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\CancellationLine;

interface CancellationLineRepositoryInterface
{
    public function save(CancellationLine $cancellationLine): void;

    public function sumAmountByDisbursementLineId(string $disbursementLineId): float;

}
