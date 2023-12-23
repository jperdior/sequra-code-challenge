<?php

declare(strict_types=1);

namespace App\SequraChallenge\Purchases\Domain\Services;

use App\SequraChallenge\Purchases\Domain\Exceptions\PurchaseNotFound;
use App\SequraChallenge\Purchases\Domain\PurchaseRepositoryInterface;

final class PurchaseFinder
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $repository
    ) {
    }

    public function __invoke(string $id): void
    {
        $purchase = $this->repository->findById($id);

        if (null === $purchase) {
            throw new PurchaseNotFound($id);
        }
    }
}