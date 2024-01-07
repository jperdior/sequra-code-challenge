<?php

namespace App\SequraChallenge\MerchantMonthlyFees\Domain\Entity;

use App\Shared\Domain\ValueObject\DateTimeValueObject;

final readonly class MerchantMonthlyFeeFirstDayOfMonth extends DateTimeValueObject
{

    public function __construct(\DateTime $value)
    {
        $value->modify('first day of this month');
        parent::__construct($value);
    }

}