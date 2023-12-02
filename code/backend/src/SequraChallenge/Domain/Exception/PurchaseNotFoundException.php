<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Exception;

class PurchaseNotFoundException extends \Exception
{

    public function __construct(string $message = 'Purchase not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }

}

