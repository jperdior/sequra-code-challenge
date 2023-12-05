<?php

declare(strict_types=1);

namespace App\Tests\Functional\SequraChallenge\Presentation\Command;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\DisbursementLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class EnqueueOrdersCommandTest extends KernelTestCase
{

    private EntityManagerInterface $em;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testExecute(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:enqueue-orders');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertEquals(0, $commandTester->getStatusCode());


        $disbursementLines = $this->em->getRepository(DisbursementLine::class)->findAll();
        $this->assertEquals(7, count($disbursementLines));

        $disbursements = $this->em->getRepository(Disbursement::class)->findAll();
        $this->assertEquals(5, count($disbursements));

        //Merchant A has 3 purchases the same day but with amounts related with different fees
        $merchantADisbursement = $this->em->getRepository(Disbursement::class)->findOneBy(['merchant' => 'aa']);
        //there should be exaclty on disbursement
        $this->assertInstanceOf(Disbursement::class,$merchantADisbursement);
        $this->assertEquals(9.75, $merchantADisbursement->getFees());
        $this->assertEquals(1120.25, $merchantADisbursement->getAmount());
        $this->assertEquals(0,$merchantADisbursement->getMonthlyFee());
        $this->assertEquals('2021-01-01', $merchantADisbursement->getDisbursedAt()->format('Y-m-d'));

        //there should be 3 lines
        $merchantADisbursementLines = $this->em->getRepository(DisbursementLine::class)->findBy(['disbursement' => $merchantADisbursement], ['createdAt' => 'ASC']);
        $this->assertEquals(3, count($merchantADisbursementLines));

        //fee for small amount applied
        $this->assertEquals(1,$merchantADisbursementLines[0]->getFeePercentage());
        $this->assertEquals(0.3, $merchantADisbursementLines[0]->getFeeAmount());
        $this->assertEquals(29.7, $merchantADisbursementLines[0]->getAmount());
        $this->assertEquals(
            $merchantADisbursementLines[0]->getPurchase()->getAmount(),
            $merchantADisbursementLines[0]->getAmount() + $merchantADisbursementLines[0]->getFeeAmount()
        );

        //fee for medium amount applied
        $this->assertEquals(0.95,$merchantADisbursementLines[1]->getFeePercentage());
        $this->assertEquals(0.95, $merchantADisbursementLines[1]->getFeeAmount());
        $this->assertEquals(99.05, $merchantADisbursementLines[1]->getAmount());
        $this->assertEquals(
            $merchantADisbursementLines[1]->getPurchase()->getAmount(),
            $merchantADisbursementLines[1]->getAmount() + $merchantADisbursementLines[1]->getFeeAmount()
        );

        //fee for large amount
        $this->assertEquals(0.85,$merchantADisbursementLines[2]->getFeePercentage());
        $this->assertEquals(8.5, $merchantADisbursementLines[2]->getFeeAmount());
        $this->assertEquals(991.5, $merchantADisbursementLines[2]->getAmount());
        $this->assertEquals(
            $merchantADisbursementLines[2]->getPurchase()->getAmount(),
            $merchantADisbursementLines[2]->getAmount() + $merchantADisbursementLines[2]->getFeeAmount()
        );

        //Merchant B live on is on friday and has weekly disbursement
        $merchantBDisbursements =  $this->em->getRepository(Disbursement::class)->findBy(['merchant' => 'bb'], ['disbursedAt'=>'ASC']);
        $this->assertEquals(4, count($merchantBDisbursements));

        //Purchase created on friday so should be disbursed the same day
        $this->assertEquals('2021-01-01',$merchantBDisbursements[0]->getDisbursedAt()->format('Y-m-d'));
        $this->assertEquals(0,$merchantBDisbursements[0]->getMonthlyFee());
        $this->assertEquals(99.05,$merchantBDisbursements[0]->getAmount());
        $this->assertEquals(0.95,$merchantBDisbursements[0]->getFees());

        //Purchase created on sunday 3 of january, so should be disbursed next friday
        $this->assertEquals('2021-01-08',$merchantBDisbursements[1]->getDisbursedAt()->format('Y-m-d'));
        $this->assertEquals(0,$merchantBDisbursements[1]->getMonthlyFee());
        $this->assertEquals(297.45,$merchantBDisbursements[1]->getAmount());
        $this->assertEquals(2.55,$merchantBDisbursements[1]->getFees());

        //Purchase created on monday 11 of january, so should be disbursed next friday
        $this->assertEquals('2021-01-15',$merchantBDisbursements[2]->getDisbursedAt()->format('Y-m-d'));
        $this->assertEquals(0,$merchantBDisbursements[2]->getMonthlyFee());
        $this->assertEquals(198.1,$merchantBDisbursements[2]->getAmount());
        $this->assertEquals(1.9,$merchantBDisbursements[2]->getFees());

        //First purchase of the month, so should contain the monthly fee
        $this->assertEquals('2021-02-05',$merchantBDisbursements[3]->getDisbursedAt()->format('Y-m-d'));
        $this->assertEquals(94.6,$merchantBDisbursements[3]->getMonthlyFee());
        $this->assertEquals(396.6,$merchantBDisbursements[3]->getAmount());
        $this->assertEquals(3.4,$merchantBDisbursements[3]->getFees());


    }
}