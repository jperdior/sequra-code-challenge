<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Application\Create;

use App\Shared\Domain\Bus\Command\Command;

final readonly class CreatePurchaseCommand implements Command
{
    public function __construct(
        public string $id,
        public \DateTime $createdAt,
        public float $amount
    ) {}

}