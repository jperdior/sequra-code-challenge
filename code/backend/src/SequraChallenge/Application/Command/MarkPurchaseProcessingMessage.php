<?php

declare(strict_types=1);

namespace App\SequraChallenge\Application\Command;

use App\SequraChallenge\Infrastructure\Messenger\CommandMessage;

readonly class MarkPurchaseProcessingMessage implements CommandMessage
{
    public function __construct(
        private string $purchaseId
    ) {
    }

    public function getPurchaseId(): string
    {
        return $this->purchaseId;
    }
}
