<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

interface TransactionInterface
{
    public function open();

    public function commit();

    public function rollback();

    public function clear();
}