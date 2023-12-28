<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantReference;
use App\SequraChallenge\Merchants\Domain\MerchantFinder;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function Lambdish\Phunctional\apply;

#[AsMessageHandler]
final readonly class FindMerchantQueryHandler
{
    public function __construct(
        private MerchantFinder $merchantFinder
    ) {
    }

    public function __invoke(FindMerchantQuery $query): Merchant
    {
        $reference = new MerchantReference($query->reference);

        return apply($this->merchantFinder, [$reference]);
    }
}