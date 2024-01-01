<?php

declare(strict_types=1);

namespace App\SequraChallenge\Merchants\Infrastructure\Persistence\Doctrine;

use App\SequraChallenge\Shared\Domain\Merchants\MerchantReference;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class MerchantReferenceType extends StringType
{
    protected function typeClassName(): string
    {
        return MerchantReference::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof MerchantReference) {
            return $value->value;
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof MerchantReference) {
            return $value;
        }

        return new MerchantReference($value);
    }
}

