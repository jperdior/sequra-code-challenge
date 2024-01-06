<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Increment;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeAmount;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeId;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeMonth;
use App\SequraChallenge\MerchantMonthlyFees\Domain\MerchantMonthlyFeeRepositoryInterface;
use App\SequraChallenge\Shared\Application\Merchants\Find\FindMerchantQuery;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Query\QueryBus;

final readonly class MerchantMonthlyFeeIncrementerUseCase
{

    public function __construct(
        private MerchantMonthlyFeeRepositoryInterface $merchantMonthlyFeeRepository,
        private QueryBus $queryBus
    ){
    }

    public function __invoke(
        MerchantReference $merchantReference,
        MerchantMonthlyFeeAmount $feeAmount,
        MerchantMonthlyFeeMonth $month
    ): void
    {
        $this->queryBus->ask(new FindMerchantQuery($merchantReference->value));

        $merchantMonthlyFee = $this->merchantMonthlyFeeRepository->search($merchantReference, $month);

        if (null == $merchantMonthlyFee){
            $merchantMonthlyFee = MerchantMonthlyFee::create(
                MerchantMonthlyFeeId::random()->value,
                $merchantReference->value,
                $month->value
            );
        }
        /**
         * @todo query for all the disbursements of the month and sum the amount
         */
        $merchantMonthlyFee->increaseAmount($feeAmount->value);

        $this->merchantMonthlyFeeRepository->save($merchantMonthlyFee);
    }
}