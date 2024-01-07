<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Application\Find;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
final readonly class FindMerchantMonthlyFeesQueryHandler implements QueryHandler
{

    public function __construct(
        private MerchantMonthlyFeesFinderUseCase $useCase,
        private MerchantMonthlyFeesResponseConverter $responseConverter
    )
    {
    }

    public function __invoke(FindMerchantMonthlyFeesQuery $query): ?MerchantMonthlyFeesResponse
    {
        $merchantMonthlyFees = apply($this->useCase, [
            new MerchantReference($query->merchantReference),
            new MerchantMonthlyFeeFirstDayOfMonth($query->firstDayOfMonth)
        ]);

        return apply($this->responseConverter, [$merchantMonthlyFees]);
    }
}