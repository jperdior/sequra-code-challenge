<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\MerchantFinder;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

use function Lambdish\Phunctional\apply;

final readonly class MerchantFinderUseCase
{
    public function __construct(
        private MerchantFinder $merchantFinder
    ) {
    }

    public function __invoke(MerchantReference $reference): Merchant
    {
        return apply($this->merchantFinder, [$reference]);
    }
}
