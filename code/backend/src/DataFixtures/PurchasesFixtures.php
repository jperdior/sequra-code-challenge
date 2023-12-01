<?php

namespace App\DataFixtures;

use App\SequraChallenge\Domain\Entity\Enum\PurchaseStatusEnum;
use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Entity\Purchase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PurchasesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

//        $purchasesCsvPath = __DIR__ . '/orders.csv';
//        $purchasesCsv = fopen($purchasesCsvPath, 'r');
//
//        //skip first line
//        fgetcsv($purchasesCsv);
//
//        $batchSize = 20;
//        $i = 0;
//
//        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
//
//        while (($purchasesCsvRow = fgetcsv($purchasesCsv, null, ';')) !== false) {
//            $purchase = new Purchase();
//            $purchase->setId($purchasesCsvRow[0]);
//            $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => $purchasesCsvRow[1]]);
//            $purchase->setMerchant($merchant);
//            $purchase->setAmount(floatval($purchasesCsvRow[2]));
//            $purchase->setCreatedAt(new \DateTime($purchasesCsvRow[3]));
//            $purchase->setStatus(PurchaseStatusEnum::PENDING->value);
//            $manager->persist($purchase);
//            $manager->detach($purchase);
//            if ($i % $batchSize === 0) {
//                $manager->flush();
//                $manager->clear();
//            }
//            $i++;
//        }
//
//        $manager->flush();
//        $manager->clear();
    }

//    public function getDependencies(): array
//    {
//        return [
//            MerchantFixtures::class,
//        ];
//    }
}
