<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\DisbursementLines\Application\Create;

use App\SequraChallenge\DisbursementLines\Application\Create\CreateDisbursementLineUseCase;
use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLine;
use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLinePurchaseAmount;
use App\SequraChallenge\DisbursementLines\Domain\Entity\DisbursementLinePurchaseId;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementLineRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Disbursements\DisbursementReference;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;

final class CreateDisbursementLineUseCaseTest extends TestCase
{

    private DisbursementLineRepositoryInterface $repository;

    private EventBus $eventBus;

    private CreateDisbursementLineUseCase $createDisbursementLineUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(DisbursementLineRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBus::class);
        $this->createDisbursementLineUseCase = new CreateDisbursementLineUseCase(
            $this->repository,
            $this->eventBus
        );
    }

    /** @test */
    public function it_should_create_a_disbursement_line(){

        $this->repository->expects($this->once())->method('findByPurchaseId')->willReturn(null);

        $this->repository->expects($this->once())->method('save');

        $this->eventBus->expects($this->once())->method('publish');

        $this->createDisbursementLineUseCase->__invoke(
            disbursementReference: DisbursementReference::random()->value,
            purchaseId: DisbursementLinePurchaseId::random()->value,
            purchaseAmount: DisbursementLinePurchaseAmount::random()->value
        );
    }

    /** @test */
    public function if_purchase_processed_it_should_skip(){
        $existingDisbursementLine = $this->createMock(DisbursementLine::class);
        $this->repository->expects($this->once())->method('findByPurchaseId')->willReturn(
            $existingDisbursementLine
        );
        $this->createDisbursementLineUseCase->__invoke(
            disbursementReference: DisbursementReference::random()->value,
            purchaseId: DisbursementLinePurchaseId::random()->value,
            purchaseAmount: DisbursementLinePurchaseAmount::random()->value
        );
    }


}