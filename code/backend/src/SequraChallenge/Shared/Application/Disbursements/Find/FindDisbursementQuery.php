<?php

declare(strict_types=1);

namespace App\SequraChallenge\Shared\Application\Disbursements\Find;

use App\Shared\Domain\Bus\Query\Query;

final readonly class FindDisbursementQuery implements Query
{
    public function __construct(
        public string $merchantReference,
        public \DateTime $createdAt,
    ) {
    }
}