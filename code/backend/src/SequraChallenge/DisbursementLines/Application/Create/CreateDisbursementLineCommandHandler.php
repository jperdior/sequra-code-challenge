<?php

declare(strict_types=1);

namespace App\SequraChallenge\DisbursementLines\Application\Create;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
readonly class CreateDisbursementLineCommandHandler
{
    public function __construct(
        private DisbursementLineCreatorUseCase $useCase,
    ) {
    }

    public function __invoke(CreateDisbursementLineCommand $command): void
    {
        apply($this->useCase, [
            $command->disbursementReference,
            $command->purchaseId,
            $command->purchaseAmount,
        ]);
    }
}