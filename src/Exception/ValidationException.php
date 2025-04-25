<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends \RuntimeException
{
    public static function fromList(ConstraintViolationList $errors): self
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return new self(implode(',', $errorMessages));
    }
}
