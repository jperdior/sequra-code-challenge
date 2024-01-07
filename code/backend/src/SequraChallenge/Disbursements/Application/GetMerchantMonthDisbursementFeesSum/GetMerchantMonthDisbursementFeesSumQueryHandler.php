<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Application\GetMerchantMonthDisbursementFeesSum;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
final readonly class GetMerchantMonthDisbursementFeesSumQueryHandler implements QueryHandler
{
    public function __construct(
        private GetMerchantMonthDisbursementFeesSumUseCase $searchMerchantMonthDisbursementsUseCase,
        private MerchantMonthDisbursementFeesSumResponseConverter $merchantMonthDisbursementFeesSumResponseConverter
    ) {
    }

    public function __invoke(GetMerchantMonthDisbursementFeesSumQuery $query): MerchantMonthDisbursementFeesSumResponse
    {
        $feesSum = apply(
            $this->searchMerchantMonthDisbursementsUseCase,
            [
                new MerchantReference($query->merchantReference),
                $query->firstDayOfMonth,
            ]
        );

        return apply($this->merchantMonthDisbursementFeesSumResponseConverter, [$feesSum]);
    }
}
