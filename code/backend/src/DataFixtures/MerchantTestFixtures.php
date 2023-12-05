<?php

namespace App\DataFixtures;

use App\SequraChallenge\Domain\Entity\Enum\DisbursementFrequencyEnum;
use App\SequraChallenge\Domain\Entity\Merchant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MerchantTestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        if (getenv('APP_ENV') !== 'test') {
            return;
        }

        //DAILY MERCHANT
        $merchant = new Merchant();
        $merchant->setId('aa');
        $merchant->setReference('aa');
        $merchant->setEmail('aaa@aaa.es');
        $merchant->setLiveOn(new \DateTime('2021-01-01'));
        $merchant->setDisbursementFrequency(DisbursementFrequencyEnum::DAILY->value);
        $merchant->setMinimumMonthlyFee(0.0);
        $manager->persist($merchant);

        //WEEKLY MERCHANT
        $merchant = new Merchant();
        $merchant->setId('bb');
        $merchant->setReference('bb');
        $merchant->setEmail('bbb@bb.es');
        //FRIDAY (5)
        $merchant->setLiveOn(new \DateTime('2021-01-01'));
        $merchant->setDisbursementFrequency(DisbursementFrequencyEnum::WEEKLY->value);
        $merchant->setMinimumMonthlyFee(100.0);
        $manager->persist($merchant);

        $manager->flush();
        $manager->clear();
    }
}
