<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantId;
use App\SequraChallenge\Merchants\Domain\Entity\MerchantReference;
use App\SequraChallenge\Merchants\Domain\Exception\MerchantNotFound;
use App\SequraChallenge\Merchants\Domain\Repository\MerchantRepositoryInterface;

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