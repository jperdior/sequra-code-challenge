<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Application\Create;

use App\Shared\Domain\Bus\Command\Command;

final readonly class CreateDisbursementLineCommand implements Command
{

    public function __construct(
        public string $disbursementReference,
        public string $purchaseId,
        public float $purchaseAmount,
    ) {
    }

}