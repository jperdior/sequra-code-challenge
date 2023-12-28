<?php

namespace App\SequraChallenge\Disbursements\Domain\Entity;

use App\Shared\Domain\Collection;

final readonly class DisbursementLines extends Collection
{

    protected function type(): string
    {
        return DisbursementLine::class;
    }

    public function add(DisbursementLine $disbursementLine): void
    {
        $this->add($disbursementLine);
    }
}