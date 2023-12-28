<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Identifiers;

interface UniqueIdGeneratorInterface
{
    public function generateUlid(): string;
}
