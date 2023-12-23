<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Exception;

class DisbursementLineNotFoundException extends \Exception
{

    public function __construct(string $message = 'Disbursement line not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }

}

