<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Presentation\Command;

use App\SequraChallenge\Purchases\Domain\DomainEvents\PurchaseCreatedDomainEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        fgetcsv($purchasesCsv);

        try {
            foreach ($this->readCsvRows($purchasesCsv) as $row) {

                $event = new PurchaseCreatedDomainEvent(
                    id: $row[0],
                    merchantReference: $row[1],
                    amount: (float) $row[2],
                    createdAt: new \DateTime($row[3])
                );
                $this->eventBus->publish($event);
                unset($event);
            }
        } finally {
            fclose($purchasesCsv);
        }

        return Command::SUCCESS;
    }

    private function readCsvRows($csvFile): iterable
    {
        while (($data = fgetcsv($csvFile, null, ';')) !== false) {
            yield $data;
        }
    }
}
