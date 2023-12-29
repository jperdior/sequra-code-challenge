<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Repository;

use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

interface DisbursementRepositoryInterface
{
    public function getByMerchantAndDisbursedDate(
        MerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): ?Disbursement;

    //public function countDisbursementsOfMonth(Merchant $merchant, \DateTime $dateTime): int;

    //public function getSumOfLastMonthFees(Merchant $merchant, \DateTime $date): float;

    public function save(Disbursement $disbursement): void;

    //public function getMerchantCurrentDisbursement(Merchant $merchant);
}
