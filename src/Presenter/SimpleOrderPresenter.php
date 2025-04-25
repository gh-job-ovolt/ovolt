<?php

declare(strict_types=1);

namespace App\Presenter;

use App\Entity\Order;

class SimpleOrderPresenter
{
//    private string $id;
//    private string $status;
//    private string $createdAt;
//    private float $total;

    public static function createOrderPresenter(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'total' => self::formatTotal($order->getTotal()),
            'status' => $order->getStatus()->value,
        ];
    }

    public static function getOrderPresenter(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'status' => $order->getStatus()->value,
            'total' => self::formatTotal($order->getTotal()),
        ];
    }

    public static function updateOrderStatusPresenter(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'status' => $order->getStatus()->value,
        ];
    }

    private static function formatTotal(int $total): float
    {
        return (float) number_format($total / 100, 2);
    }
}
