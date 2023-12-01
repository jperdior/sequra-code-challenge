<?php

declare(strict_types=1);

namespace App\SequraChallenge\Application\Command;


use App\SequraChallenge\Infrastructure\Messenger\CommandMessage;

class ProcessOldestPurchaseMessage implements CommandMessage
{
    public function __construct()
    {
    }

}