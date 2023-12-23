<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Entity\Factory;

use App\SequraChallenge\Domain\Entity\CancellationLine;
use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Identifiers\UniqueIdGeneratorInterface;

class CancellationLineFactory
{
    public function __construct(
        private UniqueIdGeneratorInterface $uniqueIdGenerator
    ) {
    }

    public function create(
    ): CancellationLine {
        $cancellationLine = new CancellationLine();
        $cancellationLine->setId($this->uniqueIdGenerator->generateUlid());
        $cancellationLine->setCreatedAt(new \DateTime());

        return $cancellationLine;
    }
}
