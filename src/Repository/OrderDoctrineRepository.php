<?php
namespace App\Repository;

use App\Common\Doctrine\DoctrineRepository;
use App\Entity\Order;
use Symfony\Component\Uid\Uuid;

class OrderDoctrineRepository extends DoctrineRepository implements OrderRepository
{
    public function add(Order $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function findByUuid(Uuid $uuid): ?Order
    {
        return $this->repository->findOneBy(['id' => $uuid->toString()]);
    }

    protected function entityClass(): string
    {
        return Order::class;
    }
}
