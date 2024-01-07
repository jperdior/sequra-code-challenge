<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Exception;

use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Domain\DomainError;

class DisbursementNotFound extends DomainError
{
    public function __construct(private readonly DisbursementReference $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'disbursement_not_exist';
    }

    protected function errorMessage(): string
    {
        return sprintf('The disbursement <%s> does not exist', $this->id->value);
    }
}
