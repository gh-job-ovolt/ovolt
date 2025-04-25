<?php
namespace App\Enum;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
}
