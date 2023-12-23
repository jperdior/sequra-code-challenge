<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Application\Create;

use App\SequraChallenge\Purchases\Domain\Purchase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreatePurchaseCommandHandler
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $repository
    ) {
    }

    public function __invoke(CreatePurchaseCommand $command): void
    {
        $purchase = Purchase::create(
            id:$command->id,
            amount: $command->amount,
            createdAt: $command->createdAt
        );
    }
}