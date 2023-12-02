<?php

declare(strict_types=1);

namespace App\SequraChallenge\Presentation\Command;

use App\SequraChallenge\Application\Command\ProcessPurchaseMessage;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Infrastructure\Messenger\SimpleCommandBus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:enqueue-orders', description: 'Enqueue orders')]
class EnqueueOrdersCommand extends Command
{
    public function __construct(
        private readonly SimpleCommandBus $commandBus,
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Enqueue purchases');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchSize = 1000;
        $offset = 0;

        do {
            $pendingPurchases = $this->purchaseRepository->getNotProcessed(
                limit: $batchSize,
                offset: $offset
            );

            foreach ($pendingPurchases as $purchase) {
                $this->commandBus->dispatch(new ProcessPurchaseMessage(
                    purchaseId: $purchase->getId()
                ));
            }

            $offset += $batchSize;

            if ($offset % 5000 === 0) {
                $this->entityManager->clear();
            }

            // Continue processing while there are more purchases
        } while (!empty($pendingPurchases));

        return Command::SUCCESS;
    }
}
