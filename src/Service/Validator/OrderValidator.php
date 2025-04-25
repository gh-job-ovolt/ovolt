<?php

declare(strict_types=1);

namespace App\Service\Validator;

use App\Entity\Order;
use App\Entity\OrderItem;
use Symfony\Component\Validator\ConstraintViolationList;

interface OrderValidator
{
    public function validateOrder(Order $order): ?ConstraintViolationList;
    public function validateOrderItem(OrderItem $item): ?ConstraintViolationList;
}
