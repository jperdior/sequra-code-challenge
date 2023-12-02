<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Exception;


class InvalidDisbursementFrequencyException extends \Exception
{

    private const MESSAGE = 'Invalid disbursement frequency';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }

}
