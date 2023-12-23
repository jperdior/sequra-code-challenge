<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Exception;


class InvalidCancellationAmountException extends \Exception
{

    private const MESSAGE = 'The cancellation amount is greater than the disbursement line amount';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }

}
