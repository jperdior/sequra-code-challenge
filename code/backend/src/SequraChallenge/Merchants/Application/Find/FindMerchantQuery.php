<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\Shared\Domain\Bus\Query\Query;

readonly class FindMerchantQuery implements Query
{
    public function __construct(
        public string $reference
    ) {
    }

}