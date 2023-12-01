<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

use App\SequraChallenge\Domain\Entity\Purchase;

interface PurchaseRepositoryInterface
{
    public function getOldestPendingPurchase();

    public function findById(string $id): ?Purchase;
}