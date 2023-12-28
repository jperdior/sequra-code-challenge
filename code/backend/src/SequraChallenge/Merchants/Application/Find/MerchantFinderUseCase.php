<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Application\Find;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantReference;
use App\SequraChallenge\Merchants\Domain\MerchantFinder as DomainMerchantFinder;

use function Lambdish\Phunctional\apply;

final class MerchantFinderUseCase
{

    public function __construct(
        private readonly DomainMerchantFinder $merchantFinder
    ) {
    }

    public function __invoke(MerchantReference $reference): Merchant
    {
        return apply($this->merchantFinder, [$reference]);
    }
}