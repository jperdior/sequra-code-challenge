<?php

declare(strict_types=1);

namespace App\SequraChallenge\Presentation\Command;

use App\SequraChallenge\Application\Command\ProcessOldestPurchaseMessage;
use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\MerchantRepositoryInterface;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Domain\Repository\TransactionRepositoryInterface;
use App\SequraChallenge\Infrastructure\Messenger\SimpleCommandBus;
use App\SequraChallenge\Infrastructure\Messenger\SimpleQueryBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Console\Input\InputArgument;
use ApiPlatform\Symfony\Validator\Exception\ValidationException;

#[AsCommand(name: 'app:import-orders', description: 'Imports orders from CSV file')]
class ImportOrdersCommand extends Command
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly MerchantRepositoryInterface $merchantRepository,
        private readonly TransactionRepositoryInterface $transactionRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Imports orders from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $purchasesCsvPath = __DIR__ . '/orders.csv';
        $purchasesCsv = fopen($purchasesCsvPath, 'r');

        //skip first line
        fgetcsv($purchasesCsv);

        $batchSize = 20;
        $i = 0;
        $this->transactionRepository->open();
        while (($purchasesCsvRow = fgetcsv($purchasesCsv, null, ';')) !== false) {
            $purchase = new Purchase();
            $purchase->setId($purchasesCsvRow[0]);
            $merchant = $this->merchantRepository->findOneBy(['reference' => $purchasesCsvRow[1]]);
            $purchase->setMerchant($merchant);
            $purchase->setAmount(floatval($purchasesCsvRow[2]));
            $purchase->setCreatedAt(new \DateTime($purchasesCsvRow[3]));
            $purchase->setStatus(PurchaseStatusEnum::PENDING->value);
            $this->purchaseRepository->save($purchase);
            $this->purchaseRepository->detach($purchase);
            if ($i % $batchSize === 0) {
                $this->transactionRepository->commit();
                $this->transactionRepository->clear();
                $this->transactionRepository->open();
            }
            $i++;
        }
        $this->transactionRepository->commit();

        return Command::SUCCESS;
    }
}
