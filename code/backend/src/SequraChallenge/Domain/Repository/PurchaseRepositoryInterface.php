<?php

declare(strict_types=1);

namespace App\SequraChallenge\Domain\Repository;

interface PurchaseRepositoryInterface
{
    public function getOldestPendingPurchase();

    public function detach(\App\SequraChallenge\Domain\Entity\Purchase $purchase);
}