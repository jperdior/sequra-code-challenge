<?php

declare(strict_types=1);

namespace App\SequraChallenge\Infrastructure\Storage\Doctrine\Repository;

use App\SequraChallenge\Domain\Entity\DisbursementLine;
use App\SequraChallenge\Domain\Repository\DisbursementLineRepositoryInterface;

class DisbursementLineRepository extends AbstractOrmRepository implements DisbursementLineRepositoryInterface
{

    protected function getClass(): string
    {
        return DisbursementLine::class;
    }

    public function save(DisbursementLine $disbursementLine)
    {
        $this->getEntityManager()->persist($disbursementLine);
        $this->getEntityManager()->flush();
    }

}