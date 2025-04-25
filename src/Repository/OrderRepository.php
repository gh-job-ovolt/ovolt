<?php
namespace App\Repository;

use App\Entity\Order;
use Symfony\Component\Uid\Uuid;

interface OrderRepository
{
    public function add(Order $order): void;

    public function findByUuid(Uuid $uuid): ?Order;
}