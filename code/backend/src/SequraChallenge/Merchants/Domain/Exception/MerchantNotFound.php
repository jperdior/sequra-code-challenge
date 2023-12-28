<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Exception;

use App\SequraChallenge\Merchants\Domain\Entity\MerchantReference;

class MerchantNotFound extends \DomainException
{
    public function __construct(private MerchantReference $id)
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return sprintf('Merchant with reference <%s> not found', $this->id->value);
    }
}