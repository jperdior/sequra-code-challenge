<?php

declare(strict_types=1);

namespace App\SequraChallenge\Presentation\Command;

use App\SequraChallenge\Application\Command\MarkPurchaseProcessingMessage;
use App\SequraChallenge\Application\Command\ProcessPurchaseMessage;
use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Infrastructure\Messenger\SimpleCommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:enqueue-orders', description: 'Enqueue orders')]
class ProcessOldestOrderCommand extends Command
{
    public function __construct(
        private readonly SimpleCommandBus $commandBus,
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly DisbursementLineRepositoryInterface $disbursementLineRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Enqueue purchases');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $pendingPurchases = $this->purchaseRepository->findBy(
            [],
            ['createdAt' => 'ASC'],
            '100'
        );

        foreach ($pendingPurchases as $purchase) {
            if ($this->disbursementLineRepository->existsByPurchase($purchase->getId())) {
                continue;
            }
            $this->commandBus->dispatch(new ProcessPurchaseMessage(
                purchaseId: $purchase->getId())
            );
        }

        return Command::SUCCESS;
    }
}
