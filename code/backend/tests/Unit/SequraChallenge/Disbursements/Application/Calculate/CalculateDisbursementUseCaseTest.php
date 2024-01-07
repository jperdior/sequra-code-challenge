<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Disbursements\Application\Create;

use App\SequraChallenge\Disbursements\Application\Calculate\CalculateDisbursementUseCase;
use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use App\SequraChallenge\Disbursements\Domain\DisbursementRepositoryInterface;
use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Merchants\Application\Find\MerchantResponse;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantDisbursementFrequency;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantMinimumMonthlyFee;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use PHPUnit\Framework\TestCase;

final class CalculateDisbursementUseCaseTest extends TestCase
{
    private EventBus $eventBus;

    private QueryBus $queryBus;

    private DisbursementRepositoryInterface $disbursementRepository;

    private DisbursementDateCalculator $disbursementDateCalculator;

    private CalculateDisbursementUseCase $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->eventBus = $this->createMock(EventBus::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->disbursementRepository = $this->createMock(DisbursementRepositoryInterface::class);
        $this->disbursementDateCalculator = new DisbursementDateCalculator();
        $this->useCase = new CalculateDisbursementUseCase(
            $this->eventBus,
            $this->queryBus,
            $this->disbursementDateCalculator,
            $this->disbursementRepository
        );
    }

    /**
     * @test
     */
    public function itShouldCalculateADisbursementForADailyMerchant(): void
    {
        $merchant = new MerchantResponse(
            reference: MerchantReference::random()->value,
            liveOn: new \DateTime('2021-01-01'),
            disbursementFrequency: MerchantDisbursementFrequency::DAILY,
            minimumMonthlyFee: MerchantMinimumMonthlyFee::random()->value
        );

        $disbursement = Disbursement::create(
            reference: DisbursementReference::random()->value,
            merchantReference: $merchant->reference,
            disbursedAt: new \DateTime('2021-01-01')
        );

        $this->queryBus->expects($this->once())
            ->method('ask')
            ->willReturn($merchant);

        $this->disbursementRepository->expects($this->once())->method('getByMerchantAndDisbursedDate')->willReturn($disbursement);

        $this->disbursementRepository->expects($this->once())->method('save');

        $this->eventBus->expects($this->once())->method('publish');

        $this->useCase->__invoke(
            merchantReference: new MerchantReference($merchant->reference),
            createdAt: new \DateTime('2021-01-01'),
            purchaseId: 'purchase-id',
            purchaseAmount: 100
        );
    }

    /**
     * @test
     */
    public function itShouldCreateANonFirstOfMonthDisbursement(): void
    {
        $merchant = new MerchantResponse(
            reference: MerchantReference::random()->value,
            liveOn: new \DateTime('2021-01-01'),
            disbursementFrequency: MerchantDisbursementFrequency::DAILY,
            minimumMonthlyFee: MerchantMinimumMonthlyFee::random()->value
        );

        $firstOfMonth = Disbursement::create(
            reference: DisbursementReference::random()->value,
            merchantReference: $merchant->reference,
            disbursedAt: new \DateTime('2021-01-01'),
            firstOfMonth: true
        );

        $this->queryBus->expects($this->once())
            ->method('ask')
            ->willReturn($merchant);

        $this->disbursementRepository->expects($this->once())->method('getByMerchantAndDisbursedDate')->willReturn(null);

        $this->disbursementRepository->expects($this->once())->method('getFirstOfMonth')->willReturn($firstOfMonth);

        $this->disbursementRepository->expects($this->once())->method('save');

        $this->eventBus->expects($this->once())->method('publish');

        $this->useCase->__invoke(
            merchantReference: new MerchantReference($merchant->reference),
            createdAt: new \DateTime('2021-01-01'),
            purchaseId: 'purchase-id',
            purchaseAmount: 100
        );
    }
}
