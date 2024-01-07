<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\Shared\Domain\Bus\Query\Response;

final readonly class MerchantResponse implements Response
{
    public function __construct(
        public string $reference,
        public \DateTime $liveOn,
        public string $disbursementFrequency,
        public float $minimumMonthlyFee
    ) {
    }
}
