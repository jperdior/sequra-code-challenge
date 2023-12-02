<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\Purchase;

interface PurchaseRepositoryInterface
{
    public function getNotProcessed(int $limit, int $offset): array;

    public function save(Purchase $purchase): void;

    public function findById(string $id): ?Purchase;
}
