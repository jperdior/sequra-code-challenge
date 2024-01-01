<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractOrmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: $this->getClass());
    }

    public function persist(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    public function remove(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush($entity);
    }

    abstract protected function getClass(): string;
}
