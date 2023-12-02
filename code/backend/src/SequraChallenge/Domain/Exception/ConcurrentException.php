<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Exception;

class ConcurrentException extends \Exception
{

    private const MESSAGE = 'Concurrent exception:';

    public function __construct(string $message, int $code = 409)
    {
        parent::__construct(self::MESSAGE . ' ' . $message, $code);
    }

}