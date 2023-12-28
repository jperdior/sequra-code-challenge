<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Repository;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

interface MerchantRepositoryInterface
{
    public function save(Merchant $merchant): void;

    public function search(MerchantReference $reference): ?Merchant;
}