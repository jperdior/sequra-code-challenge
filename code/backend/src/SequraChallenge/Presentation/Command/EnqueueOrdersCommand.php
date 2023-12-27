<?php

declare(strict_types=1);

namespace App\SequraChallenge\Presentation\Command;

use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Infrastructure\Messenger\SimpleCommandBus;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\SequraChallenge\Purchases\Domain\DomainEvents\PurchaseCreatedDomainEvent;

#[AsCommand(name: 'app:enqueue-orders', description: 'Enqueue orders')]
class EnqueueOrdersCommand extends Command
{
    public function __construct(
        private readonly EventBus $eventBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Enqueue purchases');
        $this->addArgument('orders', InputArgument::REQUIRED, 'path to CSV file with orders');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $csvPath = $input->getArgument('orders');
        $purchasesCsv = fopen($csvPath, 'r');

        if (!$purchasesCsv) {
            $output->writeln('Error opening CSV file.');
            return Command::FAILURE;
        }

        try {
            // Read CSV file with using yield
            foreach ($this->readCsvRows($purchasesCsv) as $row) {
                $event = new PurchaseCreatedDomainEvent(
                    id: $row[0],
                    merchantReference: $row[1],
                    amount: (float) $row[2],
                    createdAt: new \DateTime($row[3])
                );
                $this->eventBus->publish($event);
            }
        } finally {
            // Always close the file handle when done
            fclose($purchasesCsv);
        }

        return Command::SUCCESS;
    }

    private function readCsvRows($csvFile): iterable
    {
        while (($data = fgetcsv($csvFile)) !== false) {
            yield $data;
        }
    }
}
