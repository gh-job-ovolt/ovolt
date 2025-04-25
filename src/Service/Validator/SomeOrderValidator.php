<?php

declare(strict_types=1);

namespace App\Service\Validator;

use App\Entity\Order;
use App\Entity\OrderItem;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SomeOrderValidator implements OrderValidator
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    }

    public function validateOrder(Order $order): ?ConstraintViolationList
    {
        // TODO: Implement validateOrder() method.

        return null;
    }

    public function validateOrderItem(OrderItem $item): ?ConstraintViolationList
    {
        $errors = $this->validator->validate($item);

        return $errors->count() ? $errors : null;
    }
}
