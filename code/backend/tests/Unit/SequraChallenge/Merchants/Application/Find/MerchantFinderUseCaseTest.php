<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Merchants\Application\Find\MerchantFinderUseCase;
use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Exception\MerchantNotFound;
use App\SequraChallenge\Merchants\Domain\MerchantFinder;
use App\SequraChallenge\Merchants\Domain\Repository\MerchantRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use PHPUnit\Framework\TestCase;

final class MerchantFinderUseCaseTest extends TestCase
{
    private MerchantFinderUseCase $useCase;

    private MerchantRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(MerchantRepositoryInterface::class);
        $merchantFinder = new MerchantFinder($this->repository);
        $this->useCase = new MerchantFinderUseCase(
            $merchantFinder
        );
    }

    /** @test */
    public function itShouldFindAMerchant(): void
    {
        $merchant = $this->createMock(Merchant::class);

        $merchant->method('reference')->willReturn(
            new MerchantReference('merchant_reference')
        );

        $this->repository->expects($this->once())->method('search')->willReturn(
            $merchant
        );

        $result = $this->useCase->__invoke($merchant->reference());

        $this->assertEquals($merchant, $result);
    }

    /** @test */
    public function itShouldNotFindAMerchant(): void
    {
        $this->expectException(MerchantNotFound::class);
        $this->repository->expects($this->once())->method('search')->willReturn(
            null
        );
        $this->useCase->__invoke(new MerchantReference('merchant_reference'));
    }
}
