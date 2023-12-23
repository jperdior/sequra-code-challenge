<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Domain\UseCase;

use App\SequraChallenge\Domain\Entity\CancellationLine;
use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Entity\Enum\DisbursementFrequencyEnum;
use App\SequraChallenge\Domain\Entity\Factory\CancellationLineFactory;
use App\SequraChallenge\Domain\Entity\Factory\DisbursementFactory;
use App\SequraChallenge\Domain\Entity\Factory\DisbursementLineFactory;
use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Entity\Purchase;
use App\SequraChallenge\Domain\Repository\CancellationLineRepositoryInterface;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Domain\Repository\PurchaseRepositoryInterface;
use App\SequraChallenge\Domain\Service\DisbursementCalculatorService;
use App\SequraChallenge\Infrastructure\Identifiers\UniqueIdGenerator;
use PHPUnit\Framework\TestCase;

class DisbursementCalculatorServiceTest extends TestCase
{
    private DisbursementRepositoryInterface $disbursementRepository;
    private DisbursementLineRepositoryInterface $disbursementLineRepository;
    private PurchaseRepositoryInterface $purchaseRepository;
    private DisbursementFactory $disbursementFactory;
    private DisbursementLineFactory $disbursementLineFactory;
    private DisbursementCalculatorService $disbursementCalculatorService;

    private Merchant $dailyMerchant;
    private Purchase $smallPurchase;
    private Purchase $mediumPurchase;
    private Purchase $largePurchase;

    public function setUp(): void
    {
        $this->disbursementRepository = $this->createMock(DisbursementRepositoryInterface::class);
        $this->disbursementLineRepository = $this->createMock(DisbursementLineRepositoryInterface::class);
        $this->purchaseRepository = $this->createMock(PurchaseRepositoryInterface::class);
        $this->cancellationLineRepository = $this->createMock(CancellationLineRepositoryInterface::class);
        $this->uniqueIdGenerator = $this->createMock(UniqueIdGenerator::class);
        $this->disbursementFactory = $this->createMock(DisbursementFactory::class);
        $this->disbursementLineFactory = $this->createMock(DisbursementLineFactory::class);
        $this->cancellationLineFactory = $this->createMock(CancellationLineFactory::class);
        $this->disbursementCalculatorService = new DisbursementCalculatorService(
            $this->disbursementRepository,
            $this->disbursementLineRepository,
            $this->purchaseRepository,
            $this->cancellationLineRepository,
            $this->disbursementFactory,
            $this->disbursementLineFactory,
            $this->cancellationLineFactory,
        );

        $merchant = new Merchant();
        $merchant->setId('aa');
        $merchant->setReference('aa');
        $merchant->setEmail('aaa@aaa.es');
        $merchant->setLiveOn(new \DateTime('2021-01-01'));
        $merchant->setDisbursementFrequency(DisbursementFrequencyEnum::DAILY->value);
        $merchant->setMinimumMonthlyFee(0.0);
        $this->dailyMerchant = $merchant;

        $merchant = new Merchant();
        $merchant->setId('aa');
        $merchant->setReference('aa');
        $merchant->setEmail('aaa@aaa.es');
        $merchant->setLiveOn(new \DateTime('2021-01-01'));
        $merchant->setDisbursementFrequency(DisbursementFrequencyEnum::WEEKLY->value);
        $merchant->setMinimumMonthlyFee(0.0);
        $this->weeklyMerchant = $merchant;

        $purchase = new Purchase();
        $purchase->setId('aa');
        $purchase->setMerchant($this->dailyMerchant);
        $purchase->setAmount(30.0);
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $this->smallPurchase = $purchase;

        //medium purchase
        $purchase = new Purchase();
        $purchase->setId('bb');
        $purchase->setMerchant($this->dailyMerchant);
        $purchase->setAmount(100.0);
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $this->mediumPurchase = $purchase;

        //large purchase
        $purchase = new Purchase();
        $purchase->setId('cc');
        $purchase->setMerchant($this->dailyMerchant);
        $purchase->setAmount(1000.0);
        $purchase->setCreatedAt(new \DateTime('2021-01-01'));
        $this->largePurchase = $purchase;

    }

    public function testCalculateDisbursementNewCreated()
    {
        $disbursement = $this->createMock(Disbursement::class);
        $disbursementLine = $this->createMock(DisbursementLine::class);

        $this->disbursementRepository->expects($this->once())
            ->method('getByMerchantAndDisbursedDate')
            ->willReturn(null);

        $this->disbursementFactory->expects($this->once())
            ->method('create')
            ->willReturn($disbursement);

        $this->disbursementRepository->expects($this->once())
            ->method('getSumOfLastMonthFees')
            ->willReturn(0.0);

        $this->disbursementLineFactory->expects($this->once())
            ->method('create')
            ->willReturn($disbursementLine);

        $this->disbursementRepository->expects($this->exactly(2))
            ->method('save');

        $this->disbursementLineRepository->expects($this->once())
            ->method('save');

        $disbursement = $this->disbursementCalculatorService->calculateDisbursement($this->smallPurchase);

        $this->assertInstanceOf(Disbursement::class, $disbursement);
        
    }

    public function testCalculateDisbursementExisting()
    {
        $disbursement = $this->createMock(Disbursement::class);
        $disbursementLine = $this->createMock(DisbursementLine::class);

        $this->disbursementRepository->expects($this->once())
            ->method('getByMerchantAndDisbursedDate')
            ->willReturn($disbursement);

        $this->disbursementLineFactory->expects($this->once())
            ->method('create')
            ->willReturn($disbursementLine);

        $this->disbursementRepository->expects($this->exactly(2))
            ->method('save');

        $this->disbursementLineRepository->expects($this->once())
            ->method('save');

        $disbursement = $this->disbursementCalculatorService->calculateDisbursement($this->smallPurchase);

        $this->assertInstanceOf(Disbursement::class, $disbursement);

    }

    public function testCalculateFeePercentage()
    {
        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('calculateFeePercentage');
        $method->setAccessible(true);

        $feePercentage = $method->invoke($this->disbursementCalculatorService, $this->smallPurchase);
        $this->assertEquals(DisbursementCalculatorService::SMALL_ORDER_PERCENTAGE, $feePercentage);

        $feePercentage = $method->invoke($this->disbursementCalculatorService, $this->mediumPurchase);
        $this->assertEquals(DisbursementCalculatorService::MEDIUM_ORDER_PERCENTAGE, $feePercentage);

        $feePercentage = $method->invoke($this->disbursementCalculatorService, $this->largePurchase);
        $this->assertEquals(DisbursementCalculatorService::LARGE_ORDER_PERCENTAGE, $feePercentage);
    }

    public function testCalculateDisbursementLineSmallPurchase(){
        $disbursement = $this->createMock(Disbursement::class);
        $disbursementLine = new DisbursementLine();

        $this->disbursementLineFactory->expects($this->once())
            ->method('create')
            ->willReturn($disbursementLine);

        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('calculateDisbursementLine');
        $method->setAccessible(true);

        $disbursementLine = $method->invoke($this->disbursementCalculatorService, $this->smallPurchase, $disbursement);
        $this->assertInstanceOf(DisbursementLine::class, $disbursementLine);
        $this->assertEquals(DisbursementCalculatorService::SMALL_ORDER_PERCENTAGE, $disbursementLine->getFeePercentage());
        $this->assertEquals(0.3, $disbursementLine->getFeeAmount());
        $this->assertEquals(29.7, $disbursementLine->getAmount());

    }

    public function testCalculateDisbursementLineMediumPurchase(){
        $disbursement = $this->createMock(Disbursement::class);
        $disbursementLine = new DisbursementLine();

        $this->disbursementLineFactory->expects($this->once())
            ->method('create')
            ->willReturn($disbursementLine);

        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('calculateDisbursementLine');
        $method->setAccessible(true);

        $disbursementLine = $method->invoke($this->disbursementCalculatorService, $this->mediumPurchase, $disbursement);
        $this->assertInstanceOf(DisbursementLine::class, $disbursementLine);
        $this->assertEquals(DisbursementCalculatorService::MEDIUM_ORDER_PERCENTAGE, $disbursementLine->getFeePercentage());
        $this->assertEquals(0.95, $disbursementLine->getFeeAmount());
        $this->assertEquals(99.05, $disbursementLine->getAmount());

    }

    public function testCalculateDisbursementLineLargePurchase(){
        $disbursement = $this->createMock(Disbursement::class);
        $disbursementLine = new DisbursementLine();

        $this->disbursementLineFactory->expects($this->once())
            ->method('create')
            ->willReturn($disbursementLine);


        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('calculateDisbursementLine');
        $method->setAccessible(true);

        $disbursementLine = $method->invoke($this->disbursementCalculatorService, $this->largePurchase, $disbursement);
        $this->assertInstanceOf(DisbursementLine::class, $disbursementLine);
        $this->assertEquals(DisbursementCalculatorService::LARGE_ORDER_PERCENTAGE, $disbursementLine->getFeePercentage());
        $this->assertEquals(8.5, $disbursementLine->getFeeAmount());
        $this->assertEquals(991.5, $disbursementLine->getAmount());

    }

    public function testGetCurrentDisbursementDate(){
        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('getCurrentDisbursementDate');
        $method->setAccessible(true);

        $disbursementDate = $method->invoke($this->disbursementCalculatorService, $this->dailyMerchant);
        $today = new \DateTime();
        $this->assertEquals($today->format('Y-m-d'), $disbursementDate->format('Y-m-d'));

        $disbursementDate = $method->invoke($this->disbursementCalculatorService, $this->weeklyMerchant);
        $today = new \DateTime();
        $today->modify('next friday');
        $this->assertEquals($today->format('Y-m-d'), $disbursementDate->format('Y-m-d'));

    }

    public function testGetExistingCurrentDisbursement(){

        $disbursement = $this->createMock(Disbursement::class);

        $this->disbursementRepository->expects($this->once())
            ->method('getByMerchantAndDisbursedDate')
            ->willReturn($disbursement);

        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('getCurrentDisbursement');
        $method->setAccessible(true);

        $disbursement = $method->invoke($this->disbursementCalculatorService, $this->dailyMerchant);
        $this->assertInstanceOf(Disbursement::class, $disbursement);
    }

    public function testGetNewCurrentDisbursement(){

        $this->disbursementRepository->expects($this->once())
            ->method('getByMerchantAndDisbursedDate')
            ->willReturn(null);

        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('getCurrentDisbursement');
        $method->setAccessible(true);

        $this->disbursementFactory->expects($this->once())
            ->method('create')
            ->willReturn(new Disbursement());

        $disbursement = $method->invoke($this->disbursementCalculatorService, $this->dailyMerchant);
        $this->assertInstanceOf(Disbursement::class, $disbursement);
    }

    public function testCalculateCancellationLineNoPreviousCancellations(){

        $cancellationLine = new CancellationLine();

        $disbursementLine = new DisbursementLine();
        $disbursementLine->setId('aa');
        $disbursementLine->setPurchaseAmount(100.0);

        $this->cancellationLineFactory->expects($this->once())
            ->method('create')
            ->willReturn($cancellationLine);

        $this->cancellationLineRepository->expects($this->once())
            ->method('sumAmountByDisbursementLineId')
            ->willReturn(0.0);

        $reflectionClass = new \ReflectionClass(DisbursementCalculatorService::class);
        $method = $reflectionClass->getMethod('calculateCancellationLine');
        $method->setAccessible(true);

        $cancellationLine = $method->invoke($this->disbursementCalculatorService, $disbursementLine, 50);
        $this->assertInstanceOf(CancellationLine::class, $cancellationLine);
        $this->assertEquals(50.0, $cancellationLine->getAmount());
    }
}