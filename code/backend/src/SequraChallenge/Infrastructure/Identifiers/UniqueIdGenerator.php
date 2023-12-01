<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Identifiers;

use App\SequraChallenge\Domain\Identifiers\UniqueIdGeneratorInterface;
use Symfony\Component\Uid\Ulid;

class UniqueIdGenerator implements UniqueIdGeneratorInterface
{
    public function generateUlid(): string
    {
        return Ulid::generate();
    }
}
