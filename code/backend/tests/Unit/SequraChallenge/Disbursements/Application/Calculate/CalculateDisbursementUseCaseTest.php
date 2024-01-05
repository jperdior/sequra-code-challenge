<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Disbursements\Application\Create;

use App\SequraChallenge\Disbursements\Application\Calculate\CalculateDisbursementUseCase;
use App\SequraChallenge\Disbursements\Domain\DisbursementCalculator;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Merchants\Application\Find\MerchantResponse;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantDisbursementFrequency;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantMinimumMonthlyFee;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Event\EventBus;
use App\SequraChallenge\Disbursements\Domain\DisbursementDateCalculator;
use PHPUnit\Framework\TestCase;

final class CalculateDisbursementUseCaseTest extends TestCase
{

    private EventBus $eventBus;

    private QueryBus $queryBus;

    private DisbursementRepositoryInterface $disbursementRepository;

    private DisbursementDateCalculator $disbursementDateCalculator;

    private DisbursementCalculator $disbursementCalculator;

    private CalculateDisbursementUseCase $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->eventBus = $this->createMock(EventBus::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->disbursementRepository = $this->createMock(DisbursementRepositoryInterface::class);
        $this->disbursementDateCalculator = new DisbursementDateCalculator();
        $this->disbursementCalculator = new DisbursementCalculator(
            $this->disbursementRepository
        );
        $this->useCase = new CalculateDisbursementUseCase(
            $this->eventBus,
            $this->queryBus,
            $this->disbursementDateCalculator,
            $this->disbursementCalculator
        );
    }

    /**
     * @test
     */
    public function it_should_calculate_a_disbursement_for_a_daily_merchant(): void
    {
        $merchant = new MerchantResponse(
            reference: MerchantReference::random()->value,
            liveOn: new \DateTime('2021-01-01'),
            disbursementFrequency: MerchantDisbursementFrequency::DAILY,
            minimumMonthlyFee: MerchantMinimumMonthlyFee::random()->value
        );

        $this->queryBus->expects($this->once())
            ->method('ask')
            ->willReturn($merchant);

        $this->eventBus->expects($this->once())->method('publish');

        $this->useCase->__invoke(
            merchantReference: new MerchantReference($merchant->reference),
            createdAt: new \DateTime('2021-01-01'),
            purchaseId: 'purchase-id',
            purchaseAmount: 100
        );


    }

}
