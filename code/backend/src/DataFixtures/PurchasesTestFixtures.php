<?php

namespace App\DataFixtures;

use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Entity\Purchase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PurchasesTestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /*
        if (getenv('APP_ENV') !== 'test') {
            return;
        }

        //small purchase
        $purchase = new Purchase();
        $purchase->setId('aa');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'aa']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(30.0);
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        //medium purchase
        $purchase = new Purchase();
        $purchase->setId('bb');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'aa']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(100.0);
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        //large purchase
        $purchase = new Purchase();
        $purchase->setId('cc');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'aa']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(1000.0);
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        //purchase for weekly merchant on friday
        $purchase = new Purchase();
        $purchase->setId('dd');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'bb']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(100.0);
        //should be disbursed friday 1
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        //purchase for weekly merchant on monday
        $purchase = new Purchase();
        $purchase->setId('ee');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'bb']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(200.0);
        //should be disbursed friday 15
        $purchase->setCreatedAt(new \DateTime('2021-01-11'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        //purchase for weekly merchant on sunday
        $purchase = new Purchase();
        $purchase->setId('ff');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'bb']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(300.0);
        //should be disbursed friday 8
        $purchase->setCreatedAt(new \DateTime('2021-01-03'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        //first purchase of the month
        $purchase = new Purchase();
        $purchase->setId('gg');
        $merchant = $manager->getRepository(Merchant::class)->findOneBy(['reference' => 'bb']);
        $purchase->setMerchant($merchant);
        $purchase->setAmount(400.0);
        //should be disbursed feb friday 5
        $purchase->setCreatedAt(new \DateTime('2021-02-01'));
        $purchase->setProcessed(false);
        $purchase->setStatus(Purchase::STATUS_PENDING);
        $manager->persist($purchase);

        $manager->flush();*/
    }
}
