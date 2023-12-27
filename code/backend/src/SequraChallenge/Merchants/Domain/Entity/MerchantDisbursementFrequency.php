<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Domain\Entity;

enum MerchantDisbursementFrequency: string
{
    case DAILY = 'DAILY';
    case WEEKLY = 'WEEKLY';

}