<?php
namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Exception\ValidationException;
use App\Repository\OrderRepository;
use App\Service\Validator\OrderValidator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class OrderService
{
    private ValidatorInterface $validator;

    public function __construct(
        private OrderRepository $orderRepository,
        private OrderValidator $orderValidator,
    )
    {
    }

    public function createOrder(array $orderData): Order
    {
        $order = new Order();
        $total = 0.0;

        foreach ($orderData['items'] as $itemData) {
            $item = new OrderItem();
            $item->setProductId($itemData['productId']);
            $item->setProductName($itemData['productName']);
            $item->setPrice($itemData['price'] * 100);
            $item->setQuantity($itemData['quantity']);

            if ($errors = $this->orderValidator->validateOrderItem($item)) {
                throw ValidationException::fromList($errors);
            }

            $total += $itemData['price'] * $itemData['quantity'];

            $order->addItem($item);
        }

        $total *= 100;

        $order->setTotal($total);

        $this->orderRepository->add($order);

        return $order;
    }

    public function updateOrderStatus(string $orderId, string $newStatus): ?Order
    {
        $order = $this->orderRepository->findByUuid(Uuid::fromString($orderId));
        if (!$order) {
            return null;
        }

        try {
            $statusEnum = OrderStatus::from($newStatus);
        } catch (\ValueError $e) {
            throw new \InvalidArgumentException("Invalid status: $newStatus");
        }

        $order->setStatus($statusEnum);
        $this->orderRepository->add($order);

        return $order;
    }

    public function getOrderById(string $orderId): ?Order
    {
        return $this->orderRepository->findByUuid(Uuid::fromString($orderId));
    }
}
