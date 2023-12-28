<?php

declare(strict_types=1);

namespace App\SequraChallenge\Disbursements\Domain;

use App\SequraChallenge\Merchants\Domain\Entity\MerchantDisbursementFrequency;

class DisbursementDateCalculator
{

    public function __invoke(
        string $merchantDisbursementFrequency,
        \DateTime $merchantLiveOnDate,
        \DateTime $purchaseCreatedAt,
    ): \DateTime{
        $disbursementDate = null;
        switch ($merchantDisbursementFrequency) {
            case MerchantDisbursementFrequency::DAILY->value:
                $disbursementDate = $purchaseCreatedAt;
                break;
            case MerchantDisbursementFrequency::WEEKLY->value:
                $liveOnDayOfWeek = $merchantLiveOnDate->format('N');
                $purchaseDayOfWeek = $purchaseCreatedAt->format('N');
                if ($liveOnDayOfWeek < $purchaseDayOfWeek) {
                    $daysDifference = $purchaseDayOfWeek - $liveOnDayOfWeek;
                    $disbursementDate = $purchaseCreatedAt->modify('-'.$daysDifference.' days')->modify('+1 week');
                } elseif ($liveOnDayOfWeek > $purchaseDayOfWeek) {
                    $daysDifference = $liveOnDayOfWeek - $purchaseDayOfWeek;
                    $disbursementDate = $purchaseCreatedAt->modify('+'.$daysDifference.' days');
                } else {
                    $disbursementDate = $purchaseCreatedAt;
                }
                break;
        }
        return $disbursementDate;
    }


}