<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use Doctrine\DBAL\Types\StringType;

final class MerchantReferenceType extends StringType
{
    protected function typeClassName(): string
    {
        return MerchantReference::class;
    }
}

