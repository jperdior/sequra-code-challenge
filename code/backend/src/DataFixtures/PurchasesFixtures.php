<?php

namespace App\DataFixtures;

use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Entity\Purchase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PurchasesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        if (getenv('APP_ENV') === 'test') {
            return;
        }

        $purchasesCsvPath = __DIR__.'/orders.csv';
        $purchasesCsv = fopen($purchasesCsvPath, 'r');

        // skip first line
        fgetcsv($purchasesCsv);

        $batchSize = 20;
        $i = 0;

        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        while (($purchasesCsvRow = fgetcsv($purchasesCsv, null, ';')) !== false) {
            $purchase = new Purchase();
            $purchase->setId($purchasesCsvRow[0]);
            $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => $purchasesCsvRow[1]]);
            $purchase->setMerchant($merchant);
            $purchase->setAmount(floatval($purchasesCsvRow[2]));
            $purchase->setCreatedAt(new \DateTime($purchasesCsvRow[3]));
            $purchase->setProcessed(false);
            $purchase->setStatus(Purchase::STATUS_PENDING);
            $manager->persist($purchase);
            if (0 === $i % $batchSize) {
                $manager->flush();
                $manager->clear();
            }
            ++$i;
        }

        $manager->flush();
        $manager->clear();
    }

    public function getDependencies(): array
    {
        return [
            MerchantFixtures::class,
        ];
    }
}
