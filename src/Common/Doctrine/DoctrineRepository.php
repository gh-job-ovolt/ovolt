<?php

declare(strict_types=1);

namespace App\Common\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository
{
    protected string $entityClass;
    protected EntityRepository $repository;

    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
        $this->repository = new EntityRepository(
            $this->entityManager,
            $this->entityManager->getClassMetadata($this->entityClass())
        );
    }

    protected abstract function entityClass(): string;
}
