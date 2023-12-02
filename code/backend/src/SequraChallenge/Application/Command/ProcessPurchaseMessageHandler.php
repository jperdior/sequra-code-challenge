<?php

declare(strict_types=1);

namespace App\SequraChallenge\Application\Command;

use App\SequraChallenge\Domain\Logging\LoggingInterface;
use App\SequraChallenge\Domain\Repository\TransactionRepositoryInterface;
use App\SequraChallenge\Domain\UseCase\ProcessPurchaseUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

#[AsMessageHandler]
class ProcessPurchaseMessageHandler
{
    public function __construct(
        private readonly ProcessPurchaseUseCase $processOldestPurchaseUseCase,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly LoggingInterface $logging
    ) {
    }

    public function __invoke(ProcessPurchaseMessage $message): void
    {
        try {
            $this->logging->info('Processing purchase with id '.$message->getPurchaseId().'...');
            $this->transactionRepository->open();
            $this->processOldestPurchaseUseCase->execute(
                purchaseId: $message->getPurchaseId()
            );
            $this->transactionRepository->commit();
            $this->logging->info('Purchase with id '.$message->getPurchaseId().' processed');
        } catch (\Exception $e) {
            if ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
            }
            $this->logging->error('Error purchase with id '.$message->getPurchaseId().': '.$e->getMessage());
            $this->transactionRepository->rollback();
        }
    }
}
