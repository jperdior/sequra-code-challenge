<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Domain\Exceptions;

final class PurchaseAlreadyExists extends \DomainException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Purchase with id %s already exists', $id));
    }
}