<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
final readonly class FindMerchantQueryHandler implements QueryHandler
{
    public function __construct(
        private MerchantFinderUseCase $merchantFinderUseCase,
        private MerchantResponseConverter $merchantResponseConverter
    ) {
    }

    public function __invoke(FindMerchantQuery $query): MerchantResponse
    {
        $merchant = apply($this->merchantFinderUseCase, [
            new MerchantReference($query->reference),
        ]);

        return apply($this->merchantResponseConverter, [$merchant]);
    }
}
