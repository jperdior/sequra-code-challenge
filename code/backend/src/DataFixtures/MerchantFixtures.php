<?php

namespace App\DataFixtures;

use App\SequraChallenge\Domain\Entity\Enum\DisbursementFrequencyEnum;
use App\SequraChallenge\Domain\Entity\Merchant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MerchantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $merchantCsvPath = __DIR__.'/merchants.csv';
        $merchantCsv = fopen($merchantCsvPath, 'r');

        // skip first line
        fgetcsv($merchantCsv);
        while (($merchantCsvRow = fgetcsv($merchantCsv, null, ';')) !== false) {
            $merchant = new Merchant();
            $merchant->setId($merchantCsvRow[0]);
            $merchant->setReference($merchantCsvRow[1]);
            $merchant->setEmail($merchantCsvRow[2]);
            $merchant->setLiveOn(new \DateTime($merchantCsvRow[3]));
            $merchant->setDisbursementFrequency(DisbursementFrequencyEnum::getFromString($merchantCsvRow[4]));
            $merchant->setMinimumMonthlyFee(floatval($merchantCsvRow[5]));
            $manager->persist($merchant);
        }

        $manager->flush();
        $manager->clear();
    }
}
