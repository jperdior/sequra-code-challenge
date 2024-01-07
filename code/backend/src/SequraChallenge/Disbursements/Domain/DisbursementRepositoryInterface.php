<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Disbursements\Domain\Entity\Disbursement;
use App\SequraChallenge\Disbursements\Domain\Entity\DisbursementDisbursedAt;
use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;

interface DisbursementRepositoryInterface
{
    public function getByMerchantAndDisbursedDate(
        MerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): ?Disbursement;

    public function getFirstOfMonth(
        MerchantReference $merchantReference,
        DisbursementDisbursedAt $disbursedAt
    ): ?Disbursement;

    public function save(Disbursement $disbursement): void;

    public function getMerchantMonthDisbursementFeesSum(MerchantReference $merchantReference, \DateTime $firstDayOfMonth): float;

}
