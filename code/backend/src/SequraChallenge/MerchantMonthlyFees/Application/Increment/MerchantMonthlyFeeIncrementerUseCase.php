<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Increment;

use App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum\GetMerchantMonthDisbursementFeesSumQuery;
use App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum\MerchantMonthDisbursementFeesSumResponse;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeId;
use App\SequraChallenge\MerchantMonthlyFees\Domain\MerchantMonthlyFeeRepositoryInterface;
use App\SequraChallenge\Merchants\Application\Find\FindMerchantQuery;
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
        MerchantMonthlyFeeFirstDayOfMonth $firstDayOfMonth
    ): void
    {
        $this->queryBus->ask(new FindMerchantQuery($merchantReference->value));

        $merchantMonthlyFee = $this->merchantMonthlyFeeRepository->search($merchantReference, $firstDayOfMonth);

        if (null == $merchantMonthlyFee){
            $merchantMonthlyFee = MerchantMonthlyFee::create(
                MerchantMonthlyFeeId::random()->value,
                $merchantReference->value,
                $firstDayOfMonth->value
            );
        }

        /**
         * @var MerchantMonthDisbursementFeesSumResponse $monthlyFeesResponse
         */
        $monthlyFeesResponse = $this->queryBus->ask(new GetMerchantMonthDisbursementFeesSumQuery($merchantReference->value, $firstDayOfMonth->value));
        $merchantMonthlyFee->updateAmount($monthlyFeesResponse->feesSum);

        $this->merchantMonthlyFeeRepository->save($merchantMonthlyFee);
    }
}