<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\Disbursement;
use App\SequraChallenge\Domain\Entity\Merchant;

interface DisbursementRepositoryInterface
{
    public function getByMerchantAndDisbursedDate(Merchant $merchant, \DateTime $createdAt): ?Disbursement;

    public function getFirstOfMonth(Merchant $merchant, \DateTime $dateTime): ?Disbursement;

    public function getSumOfLastMonthFees(Merchant $merchant, \DateTime $date): float;

    public function save(Disbursement $disbursement): void;
}
