<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Merchants\Domain\Entity\Merchant;
use App\SequraChallenge\Merchants\Domain\Repository\MerchantRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Infrastructure\Doctrine\AbstractOrmRepository;

class MerchantDoctrineRepository extends AbstractOrmRepository implements MerchantRepositoryInterface
{
    protected function getClass(): string
    {
        return Merchant::class;
    }

    public function save(Merchant $merchant): void
    {
        $this->persist($merchant);
    }

    public function search(MerchantReference $reference): ?Merchant
    {
        return $this->findOneBy(['reference' => $reference->value]);
    }

}
