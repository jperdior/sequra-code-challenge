<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain\Repository;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\Merchant;

interface DisbursementRepositoryInterface
{
    public function getByMerchantAndDisbursedDate(Merchant $merchant, \DateTime $disbursementDate): ?Disbursement;

    public function countDisbursementsOfMonth(Merchant $merchant, \DateTime $dateTime): int;

    public function getSumOfLastMonthFees(Merchant $merchant, \DateTime $date): float;

    public function save(Disbursement $disbursement): void;

    public function getMerchantCurrentDisbursement(Merchant $merchant);
}
