<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Exception\MerchantNotFound;
use App\SequraChallenge\Merchants\Domain\Repository\MerchantRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

class MerchantFinder
{

    public function __construct(
        private MerchantRepositoryInterface $repository
    ) {
    }

    public function __invoke(MerchantReference $reference): Merchant
    {
        $merchant = $this->repository->search($reference);
        if (null === $merchant) {
            throw new MerchantNotFound($reference);
        }
        return $merchant;
    }



}