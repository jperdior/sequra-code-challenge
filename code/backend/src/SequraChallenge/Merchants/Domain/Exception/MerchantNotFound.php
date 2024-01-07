<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Exception;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\DomainError;

class MerchantNotFound extends DomainError
{
    public function __construct(private readonly MerchantReference $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'merchant_not_exist';
    }

    protected function errorMessage(): string
    {
        return sprintf('The merchant <%s> does not exist', $this->id->value);
    }
}
