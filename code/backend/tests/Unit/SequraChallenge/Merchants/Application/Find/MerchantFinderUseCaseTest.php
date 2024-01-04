<?php

declare(strict_types=1);

namespace App\Tests\Unit\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Merchants\Application\Find\MerchantFinderUseCase;
use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Repository\MerchantRepositoryInterface;
use App\SequraChallenge\Merchants\Domain\MerchantFinder;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use PHPUnit\Framework\TestCase;

final class MerchantFinderUseCaseTest extends TestCase
{

    private MerchantFinderUseCase $useCase;

    private MerchantRepositoryInterface $repository;

    private MerchantFinder $merchantFinder;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(MerchantRepositoryInterface::class);
        $this->merchantFinder = new MerchantFinder($this->repository);
        $this->useCase = new MerchantFinderUseCase(
            $this->merchantFinder
        );
    }

    /** @test */
    public function it_should_find_a_merchant(): void
    {
        $this->useCase->__invoke(MerchantReference::random());
        $merchant = $this->createMock(Merchant::class);

        $this->repository->expects($this->once())->method('search')->willReturn(
            $merchant
        );

        $this->merchantFinder->expects($this->once())->method('__invoke')->willReturn(
            $merchant
        );


    }
}