<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\Merchant;
use App\SequraChallenge\Domain\Repository\MerchantRepositoryInterface;

class MerchantRepository extends AbstractOrmRepository implements MerchantRepositoryInterface
{
    protected function getClass(): string
    {
        return Merchant::class;
    }
}
