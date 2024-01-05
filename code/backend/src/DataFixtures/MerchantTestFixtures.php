<?php

namespace App\DataFixtures;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;

use App\SequraChallenge\Merchants\Domain\Entity\MerchantDisbursementFrequency;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantLiveOn;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantMinimumMonthlyFee;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
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
        $merchant = new Merchant(
            reference: new MerchantReference('aa1'),
            liveOn: new MerchantLiveOn(new \DateTime('2021-01-01')),
            disbursementFrequency: new MerchantDisbursementFrequency(MerchantDisbursementFrequency::DAILY),
            minimumMonthlyFee: new MerchantMinimumMonthlyFee(0.0)
        );
        $manager->persist($merchant);

        $merchant = new Merchant(
            reference: new MerchantReference('aa2'),
            liveOn: new MerchantLiveOn(new \DateTime('2021-01-01')),
            disbursementFrequency: new MerchantDisbursementFrequency(MerchantDisbursementFrequency::DAILY),
            minimumMonthlyFee: new MerchantMinimumMonthlyFee(0.0)
        );
        $manager->persist($merchant);

        $merchant = new Merchant(
            reference: new MerchantReference('aa3'),
            liveOn: new MerchantLiveOn(new \DateTime('2021-01-01')),
            disbursementFrequency: new MerchantDisbursementFrequency(MerchantDisbursementFrequency::DAILY),
            minimumMonthlyFee: new MerchantMinimumMonthlyFee(0.0)
        );
        $manager->persist($merchant);

        //WEEKLY MERCHANT
        $merchant = new Merchant(
            reference: new MerchantReference('bb'),
            liveOn: new MerchantLiveOn(new \DateTime('2021-01-01')),
            disbursementFrequency: new MerchantDisbursementFrequency(MerchantDisbursementFrequency::WEEKLY),
            minimumMonthlyFee: new MerchantMinimumMonthlyFee(100.0)
        );
        $manager->persist($merchant);

        $manager->flush();
        $manager->clear();
    }
}
