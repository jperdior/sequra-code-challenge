<?php

declare(strict_types=1);

namespace App\SequraChallenge\MerchantMonthlyFees\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFee;
use App\SequraChallenge\MerchantMonthlyFees\Domain\Entity\MerchantMonthlyFeeFirstDayOfMonth;
use App\SequraChallenge\MerchantMonthlyFees\Domain\MerchantMonthlyFeeRepositoryInterface;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use App\Shared\Infrastructure\Doctrine\AbstractOrmRepository;

class MerchantMonthlyFeeDoctrineRepository extends AbstractOrmRepository implements MerchantMonthlyFeeRepositoryInterface
{
    public function getClass(): string
    {
        return MerchantMonthlyFee::class;
    }

    public function save(MerchantMonthlyFee $disbursementMonthlyFee): void
    {
        $this->persist($disbursementMonthlyFee);
    }

    public function search(MerchantReference $merchantReference, MerchantMonthlyFeeFirstDayOfMonth $firstDayOfMonth): ?MerchantMonthlyFee
    {
        return $this->findOneBy(['merchantReference' => $merchantReference->value, 'firstDayOfMonth.value' => $firstDayOfMonth->value]);
    }
}
