<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\MerchantMonthlyFees\Domain\MerchantMonthlyFeeRepositoryInterface;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\Shared\Infrastructure\Doctrine\AbstractOrmRepository;

class MerchantMonthlyFeeDoctrineRepository extends AbstractOrmRepository implements MerchantMonthlyFeeRepositoryInterface{


    public function getClass(): string
    {
        return MerchantMonthlyFee::class;

    }

    public function save(MerchantMonthlyFee $disbursementMonthlyFee): void
    {
        $this->persist($disbursementMonthlyFee);
    }
}