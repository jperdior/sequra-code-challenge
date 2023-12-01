<?php

declare(strict_types=1);

namespace App\SequraChallenge\Application\Command;

use App\SequraChallenge\Domain\Logging\LoggingInterface;
use App\SequraChallenge\Domain\Repository\TransactionRepositoryInterface;
use App\SequraChallenge\Domain\UseCase\MarkPurchaseProcessingUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MarkPurchaseProcessingMessageHandler
{
    public function __construct(
        private readonly MarkPurchaseProcessingUseCase  $markPurchaseProcessingUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly LoggingInterface               $logging
    ) {
    }

    public function __invoke(MarkPurchaseProcessingMessage $message): void
    {
        try{
            $this->logging->info('Processing oldest purchase');
            $this->transactionRepository->open();
            $this->markPurchaseProcessingUseCase->execute(
                purchaseId: $message->getPurchaseId()
            );
            $this->transactionRepository->commit();
            $this->logging->info('Oldest purchase processed');
        }
        catch(\Exception $e){
            $this->logging->error('Error processing oldest purchase: '.$e->getMessage());
            $this->transactionRepository->rollback();
        }

    }
}
