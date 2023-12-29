<?php

namespace App\DataFixtures;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MerchantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        if (getenv('APP_ENV') === 'test') {
            return;
        }

        $merchantCsvPath = __DIR__.'/merchants.csv';
        $merchantCsv = fopen($merchantCsvPath, 'r');

        // skip first line
        fgetcsv($merchantCsv);
        while (($merchantCsvRow = fgetcsv($merchantCsv, null, ';')) !== false) {
            $merchant = Merchant::create(
                reference: $merchantCsvRow[1],
                liveOn: new \DateTime($merchantCsvRow[3]),
                disbursementFrequency: $merchantCsvRow[4],
                minimumMonthlyFee: floatval($merchantCsvRow[5])
            );
            $manager->persist($merchant);
        }

        $manager->flush();
        $manager->clear();
    }
}
