<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Disbursements\Domain\DisbursementCalculator;
use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Disbursements\Domain\Repository\DisbursementRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use PHPUnit\Framework\TestCase;

class DisbursementCalculatorTest extends TestCase
{

    private DisbursementRepositoryInterface $repository;

    private DisbursementCalculator $disbursementCalculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(DisbursementRepositoryInterface::class);
        $this->disbursementCalculator = new DisbursementCalculator(
            $this->repository
        );
    }

    /**
     * @test
     */
    public function it_should_create_a_new_disbursement_if_not_exists(): void
    {
        $this->repository->expects($this->once())
            ->method('getByMerchantAndDisbursedDate')
            ->willReturn(null);

        $this->repository->expects($this->once())
            ->method('save');

        $disbursement = $this->disbursementCalculator->__invoke(
            merchantReference: MerchantReference::random(),
            disbursedAt: new DisbursementDisbursedAt(new \DateTime('2021-01-01'))
        );

        $this->assertInstanceOf(Disbursement::class, $disbursement);

    }

}